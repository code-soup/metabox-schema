# Proposed additional agent skills

**Status:** Proposal for maintainer review (not implemented as `skills/*/SKILL.md` yet)  
**Package version:** 1.3.0  

These complement the existing six skills after the [skills audit](skills-audit-report.md). Each proposal includes scope, when to use, and suggested file layout.

---

## 1. `wordpress-metabox-integration`

**Problem:** Developers copy `docs/wordpress-metabox.php` but struggle with `save_post`, nonces, capabilities, and wiring `$_POST[ $prefix ]` to `Validator`.

**When to use:** Building admin metaboxes, options pages, or custom post type screens.

**Should cover:**

- `add_meta_box` / `register_setting` patterns
- Reading POST with correct `form_prefix`
- Persisting `$validated` meta with `update_post_meta` / `update_option`
- Capability checks (`current_user_can`) and nonces (WordPress APIs, not package APIs)
- Displaying validation errors on re-render via `values` or entity

**Suggested location:** `skills/wordpress-metabox-integration/SKILL.md`  
**Examples:** `examples/save-post-metabox.md`, `examples/options-page.md`

**Priority:** High — closes the gap between “render fields” and “ship a metabox”.

---

## 2. `extend-core-classes`

**Problem:** `extend-validator.php` and `extend-renderer.php` exist in `docs/` but are not reflected in skills; agents still invent wrong method names (`validate_value`).

**When to use:** Subclassing `Validator` or `Renderer`, custom grid markup, custom error handling.

**Should cover:**

- `Validator`: override `has_validation_errors`, `sanitize_value`, `build_field_context` — not removed APIs
- `Renderer`: `render_field`, `create_field`, `get_grid_class`, `merge_global_defaults`
- When to extend vs register custom field types

**Suggested location:** `skills/extend-core-classes/SKILL.md`  
**Examples:** Link or embed trimmed copies of `docs/extend-validator.php`, `docs/extend-renderer.php`

**Priority:** High — reduces invalid extension code.

---

## 3. `security-and-escaping`

**Problem:** Templates, HTML fields, checkbox POST tampering, and attribute allowlists are easy to get wrong.

**When to use:** Custom templates, `html` fields, public-facing forms, security review.

**Should cover:**

- Escaped getters vs raw getters (`get_escaped_*`, `wp_kses` in HTML/help templates)
- Validator option allowlists (`select`, `radio`, `checkbox_group`)
- Reserved attributes `id` / `name`
- `allowed_html` on HTML fields — trust boundaries
- Media field admin JS (do not treat attachment metadata as safe HTML)

**Suggested location:** `skills/security-and-escaping/SKILL.md`  
**Examples:** `examples/safe-custom-template.md`, `examples/html-field-allowlist.md`

**Priority:** Medium — especially for agents generating templates.

---

## 4. `packaging-and-releases`

**Problem:** Dual distribution (Composer library + optional WordPress plugin bootstrap) and version sync are undocumented in skills.

**When to use:** Cutting releases, wordpress.org zips, cache busting for media assets.

**Should cover:**

- `docs/release-version-sync.md` checklist
- `METABOX_SCHEMA_VERSION`, `composer.json` version, `readme.txt` Stable tag
- `composer install --no-dev` before plugin zip
- What to exclude from release archives (`vendor` policy, `docs/` vs `skills/`)

**Suggested location:** `skills/packaging-and-releases/SKILL.md`  
**Examples:** `examples/github-release-zip.md`

**Priority:** Medium — maintainer-focused; optional for Skillshare end users.

---

## 5. `testing-and-ci` (after PHPUnit lands)

**Problem:** [Testing automation proposal](testing-automation-proposal.md) defines PHPUnit + CI; agents need a skill once `tests/` exists.

**When to use:** Adding tests, interpreting CI failures, Brain Monkey stubs.

**Should cover:**

- `composer test` / `composer ci`
- Where to add Validator vs Renderer tests
- Brain Monkey patterns for `sanitize_*` stubs

**Suggested location:** `skills/testing-and-ci/SKILL.md`  
**Dependency:** Implement Phase 1–2 of testing proposal first.

**Priority:** Low until CI exists — then High.

---

## 6. `schema-migration` (optional)

**Problem:** Breaking changes (1.1 instance registration, wrapper markup in 1.3) are only in CHANGELOG.

**When to use:** Upgrading consumer plugins across package versions.

**Should cover:**

- 1.0 → 1.1: `Field_Factory::register_field_type` removed
- 1.2 → 1.3: wrapper `id`/`class`, help position, radio/checkbox types
- Validator behavior changes (defaults, checkbox_group)

**Suggested location:** `skills/schema-migration/SKILL.md`  
**Examples:** Versioned migration snippets

**Priority:** Low — valuable for long-term consumers.

---

## Recommended implementation order

| Order | Skill | Rationale |
|-------|--------|-----------|
| 1 | `wordpress-metabox-integration` | Most common end-to-end task |
| 2 | `extend-core-classes` | Prevents broken subclasses |
| 3 | `security-and-escaping` | Templates + POST safety |
| 4 | `packaging-and-releases` | Release hygiene |
| 5 | `testing-and-ci` | After test harness merge |
| 6 | `schema-migration` | Optional reference |

---

## Skillshare / metadata conventions

For each new skill:

- Set `metadata.version` to match package minor (e.g. `"1.3"`)
- Set `location` to primary PHP entry (class file)
- Keep examples as `.md` under `examples/`; reference real `template.php` paths in prose
- Cross-link existing skills in **See Also** (avoid duplicating schema-definition content)

---

## Not recommended as separate skills

| Idea | Reason |
|------|--------|
| Duplicate `radio`/`checkbox` only skill | Covered in schema-definition + `docs/radio-checkbox-fields.md` |
| WordPress coding standards | Use repo `phpcs.xml` / contributor docs, not Agent Skills |
| Full `docs/` mirror | Too large; link from focused skills instead |
