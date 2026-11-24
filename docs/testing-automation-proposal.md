# Automated Testing Proposal — Metabox Schema

**Status:** Draft for review  
**Audience:** Maintainers integrating CI before / alongside the 1.3.0 release  
**Last updated:** 2026-07-05  

## 1. Goals

| Goal | Why it matters |
|------|----------------|
| Catch regressions in **Validator** and **Renderer** | Highest user impact; recent 1.2.x / 1.3.0 work touched both heavily |
| Lock in **security-sensitive** behavior | Option allowlists, escaping assumptions, empty/required semantics |
| Run checks on every PR | No manual `composer lint` before merge |
| Stay lightweight | Library, not a full plugin; avoid a WordPress Docker stack unless we need it |

**Non-goals (initially):**

- Browser/E2E tests (Playwright) for media field JS
- Testing WordPress core or third-party themes
- 100% line coverage on templates (HTML output is better suited to focused snapshot tests)

## 2. Current state

| Area | Today |
|------|--------|
| Static analysis | `composer lint` (parallel-lint), `composer lint:phpcs` (WPCS) |
| Automated tests | None |
| CI | No `.github/workflows` |
| Runtime | Hard dependency on WordPress functions (`sanitize_*`, `esc_*`, `wp_kses`, hooks, media APIs) |

The package is a **Composer library** with a **classmap** autoload of `includes/`. Tests should live outside `includes/` and use Composer `autoload-dev`.

## 3. Recommended approach (testing pyramid)

```
                    ┌─────────────────┐
                    │  Few integration │  Optional: wp-env + one metabox smoke test
                    │  (later phase)   │
                    └────────┬────────┘
               ┌─────────────┴─────────────┐
               │   Component / output tests │  Renderer + field HTML (buffered)
               └─────────────┬─────────────┘
          ┌──────────────────┴──────────────────┐
          │         Unit tests (PHPUnit)         │  Validator, Config_Sanitizer,
          │         + WP function stubs          │  Value_Resolver, field helpers
          └─────────────────────────────────────┘
```

**Default recommendation:** Start with **PHPUnit + Brain Monkey** (mock WordPress functions and hooks). Add **GitHub Actions** running lint + tests on PHP 8.1–8.3. Defer full **WordPress test suite** until we need real `wp_editor()` / `wp_enqueue_media()` behavior in CI.

### Why Brain Monkey first?

- Validator and sanitizer call many `sanitize_*` / `esc_*` functions; Brain Monkey lets us stub them predictably without bootstrapping WordPress.
- Renderer tests can stub `checked()`, `selected()`, `esc_attr()`, etc., and assert buffered HTML.
- Faster CI (seconds, not minutes), no DB, no WP download.

### When to add wp-phpunit / wp-env

Consider when we need:

- Real `wp_kses()` behavior with production allowlists
- `WP_Editor_Field` or `Media_Field` enqueue/render integration
- Regression tests that depend on WordPress version

## 4. Tooling stack (proposed)

| Tool | Role |
|------|------|
| [PHPUnit](https://phpunit.de/) 10.x | Test runner (PHP 8.1 compatible) |
| [Brain Monkey](https://brain-wp.github.io/BrainMonkey/) | Mock `functions.php` APIs and actions/filters |
| [Yoast PHPUnit Polyfills](https://github.com/Yoast/PHPUnit-Polyfills) | PHPUnit version compatibility |
| GitHub Actions | `composer install`, `lint`, `test` on push/PR |

**Optional later:**

| Tool | Role |
|------|------|
| [PHPStan](https://phpstan.org/) + WPCS | Static types (strict_types already used) |
| [Infection](https://infection.github.io/) | Mutation testing on Validator |
| `@wordpress/env` | Containerized WP for one integration job |

### Composer changes (sketch)

```json
{
  "require-dev": {
    "phpunit/phpunit": "^10.5",
    "brain/monkey": "^2.6",
    "yoast/phpunit-polyfills": "^2.0"
  },
  "autoload-dev": {
    "psr-4": {
      "CodeSoup\\MetaboxSchema\\Tests\\": "tests/"
    }
  },
  "scripts": {
    "test": "phpunit",
    "test:coverage": "phpunit --coverage-text",
    "ci": [
      "@lint",
      "@lint:phpcs",
      "@test"
    ]
  }
}
```

`phpunit.xml.dist` at repo root: bootstrap `tests/bootstrap.php` that loads Composer autoload + Brain Monkey + a small **WP stub file** for functions we prefer not to mock per test (e.g. `__()` returning the string as-is).

## 5. Repository layout

```
tests/
├── bootstrap.php              # Autoload, Brain Monkey, default stubs
├── stubs/
│   └── wordpress-functions.php # Minimal implementations if needed
├── Unit/
│   ├── Validator/
│   │   ├── EmptyValueTest.php
│   │   ├── DefaultResolutionTest.php
│   │   ├── SelectRadioOptionsTest.php
│   │   └── CheckboxGroupTest.php
│   ├── ConfigSanitizerTest.php
│   └── ValueResolverTest.php
├── Component/
│   ├── RendererOutputTest.php
│   └── Fields/
│       ├── RadioFieldTest.php
│       └── CheckboxGroupFieldTest.php
└── Fixtures/
    └── schemas/               # JSON or PHP arrays shared by tests
```

Keep **fixtures** as plain PHP arrays mirroring `docs/` examples so tests double as executable specs.

## 6. What to test first (priority backlog)

Aligned with recent review findings and 1.3.0 features.

### P0 — Validator (unit)

| Case | Assert |
|------|--------|
| Empty POST key, optional field with `default` | Resolved value equals default (including callable) |
| Empty POST key, `required` | Error + default on failure path |
| Empty array `[]`, `required` `checkbox_group` | Treated as empty → required error |
| `checkbox_group` with invalid option in POST | Error; no unknown values in output |
| `select` / `radio` invalid option | Error |
| `number` min/max with floats | Epsilon behavior |
| `0` / `'0'` not empty | Still valid |
| Skip types `html`, `label`, `help` | Not present in validated output |

### P1 — Config sanitizer (unit)

| Case | Assert |
|------|--------|
| Invalid `wrapper` tag | Falls back to default |
| `options` optgroup | Keys/labels sanitized |
| Non-scalar `attributes` | Stripped |

### P2 — Renderer / fields (component)

| Case | Assert |
|------|--------|
| Grid auto-close | Buffered output contains single grid wrapper |
| `help_position` before/after | Help markup order |
| `wrapper_class` + required class | Classes on wrapper `id="*-wrap"` |
| Radio / checkbox_group | Escaped names, option allowlist in HTML |
| Custom field registration on Renderer instance | Factory uses registered class |

Use `ob_start()` / `Renderer::render()` or instance `render_fields()` and assert substring or DOM (optional: `symfony/dom-crawler` — only if worth the dependency).

### P3 — Regression guards (after P0–P2)

- Entity method vs callable resolution (`Value_Resolver`)
- `codesoup_metabox_schema_sanitize_default` filter
- Field-level filters `codesoup_metabox_field_*`

### P4 — Integration (optional)

- One `docs/wordpress-metabox.php`-style flow under wp-env: save POST, validate, assert post meta (manual nightly or weekly workflow).

## 7. CI workflow (GitHub Actions)

Proposed file: `.github/workflows/ci.yml`

```yaml
name: CI

on:
  push:
    branches: [master, 'release/**']
  pull_request:

jobs:
  test:
    runs-on: ubuntu-latest
    strategy:
      fail-fast: false
      matrix:
        php: ['8.1', '8.2', '8.3']
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php }}
          coverage: none
      - run: composer install --prefer-dist --no-progress
      - run: composer ci
```

**Branch policy (suggested):** Require `CI` to pass before merge to `master` and `release/*`.

**Artifacts:** Optionally upload PHPUnit JUnit XML for GitHub Checks UI (`phpunit --log-junit`).

## 8. Phased rollout

| Phase | Deliverable | Depends on |
|-------|-------------|------------|
| **0** | Approve this proposal; agree on Brain Monkey vs full WP bootstrap | — |
| **1** | `phpunit.xml.dist`, `tests/bootstrap.php`, P0 Validator tests, `composer test`, local green | Phase 0 |
| **2** | `.github/workflows/ci.yml`, `composer ci`, branch protection | Phase 1 |
| **3** | P1–P2 tests; README “Contributing” section with `composer test` | Phase 2 |
| **4** | Coverage threshold (e.g. 70% on `includes/core`) or mutation testing on Validator | Phase 3 |
| **5** | wp-env integration job (if needed) | Phase 4 + maintainer time |

Phases 1–2 are enough to **automate regression protection** before tagging 1.3.0; phases 3–5 deepen confidence.

## 9. Conventions

- **Test class naming:** `*Test.php`, one public behavior per test method (`test_required_checkbox_group_rejects_empty_array`).
- **Data providers:** Use for option validation matrix (select, radio, checkbox_group).
- **No network in tests.**
- **Docs:** When fixing a bug (e.g. empty default), add a test named after the bug class; link from CHANGELOG if user-facing.
- **PHPCS:** Add `tests/` to phpcs.xml with relaxed rules if needed, or exclude tests from WPCS and rely on PHPUnit only.

## 10. Risks and mitigations

| Risk | Mitigation |
|------|------------|
| Brain Monkey stubs diverge from real WordPress | Spot-check critical paths with wp-env job later; stub only what we use |
| HTML tests brittle on whitespace | Assert on normalized HTML or key substrings, not full file snapshots |
| CI slower after wp-env | Keep integration in a separate optional workflow |
| Dual PR branches (release vs master) | Run CI on all PR targets; same `ci.yml` |

## 11. Open decisions (need maintainer input)

1. **PHPUnit 10 only** (PHP 8.1+) vs PHPUnit 9 for broader hosting — recommend 10 to match `composer.json` platform 8.1.
2. **Coverage gate** in CI — yes/no and minimum %?
3. **Test PR target:** land testing infra on `master` first, then cherry-pick to `release/1.3.0`, or vice versa?
4. **Media field JS:** PHPUnit irrelevant; document manual test checklist or add Playwright in a separate proposal?

## 12. Next steps after approval

1. Open implementation PR: Phase 1 + 2 (bootstrap, Validator P0, GitHub Actions).
2. Add `composer ci` to README Contributing section.
3. Enable required status check on GitHub.
4. Schedule P1–P2 tests as follow-up issues (one issue per area for contributors).

---

**Related:** [PR #7](https://github.com/code-soup/metabox-schema/pull/7) / [PR #8](https://github.com/code-soup/metabox-schema/pull/8) (1.3.0 fixes) — P0 test list above maps directly to those changes once merged.
