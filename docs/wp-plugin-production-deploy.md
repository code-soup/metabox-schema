# WordPress plugin production deploy

This repository is primarily a **Composer library** (`codesoup/metabox-schema`). For **WordPress plugin** distribution (zip / svn / direct `wp-content/plugins` install), we use the shared GitHub Action:

[code-soup/wp-plugin-production-deploy](https://github.com/code-soup/wp-plugin-production-deploy)

## Why a separate `plugin` branch?

The action builds a **minimal runtime tree** (PHP + `vendor/` + plugin bootstrap) and **force-pushes** it to a target branch. That pattern is ideal for plugin-only repos where `master` is the installable plugin.

This repo also keeps **full source** on `master` (docs, skills, PHPCS, `composer.json`, etc.). Pushing the production tree to `master` would remove the library layout, so the workflow sets:

| Branch | Role |
|--------|------|
| `master` (and feature branches) | Library + plugin source, Composer, docs, skills |
| `develop` (optional) | Integration branch; pushes here trigger deploy (see below) |
| **`plugin`** | **Production plugin drop** — install or zip this branch |

If you later adopt a strict **develop → master** plugin-only `master`, change `target-branch` in `.github/workflows/wp-plugin-production-deploy.yml` and migrate library releases to tags / Packagist from `develop`.

## What gets deployed

Copied into the release (then `.deployignore` is applied):

- `includes/`
- `vendor/` (from `composer install --no-dev` in the action)
- `assets/`
- `metabox-schema.php`
- `readme.txt`
- `CHANGELOG.md`
- `LICENSE`

Excluded (among others): `skills/`, `docs/`, `.github/`, `composer.json`, dev `vendor/bin`, PHPCS, tests.

## Requirements

1. **Plugin bootstrap** — `metabox-schema.php` defines `METABOX_SCHEMA_VERSION` and loads `vendor/autoload.php`.
2. **Composer** — `composer.json` at repo root; action runs production install before copy.
3. **npm** — The action always runs `npm ci` and `npm run build:prod`. This repo uses a **no-op** `package.json` / `package-lock.json` (no frontend bundle).
4. **GitHub** — Workflow needs `contents: write` to force-push `plugin`.

## Running a deploy

### Automatic (after you create `develop`)

```bash
git checkout -b develop
git push -u origin develop
```

Pushes to `develop` run `.github/workflows/wp-plugin-production-deploy.yml`.

### Manual (any ref)

GitHub → **Actions** → **WordPress plugin production deploy** → **Run workflow**

- **source_ref:** e.g. `master`, `release/1.3.0`, or a tag

## Installing the plugin

```bash
cd wp-content/plugins
git clone --branch plugin --depth 1 https://github.com/code-soup/metabox-schema.git metabox-schema
```

Or download a zip of the `plugin` branch. Activate **Metabox Schema** in wp-admin.

## Version sync

Bump versions in `metabox-schema.php`, `readme.txt`, and `composer.json` before deploying — see `docs/release-version-sync.md`.

## Action reference

| Input | This repo |
|-------|-----------|
| `target-branch` | `plugin` |
| `php-version` | `8.1` |
| `production-files` | See workflow YAML |
| `use-deployignore` | `true` (`.deployignore` at repo root) |

Upstream defaults and examples: [wp-plugin-production-deploy README](https://github.com/code-soup/wp-plugin-production-deploy/blob/master/README.md).
