# Release version sync

When shipping a new release, update **all** of the following to the same version string (e.g. `1.3.0`):

| Location | Field |
|----------|--------|
| `metabox-schema.php` | `Version` header and `METABOX_SCHEMA_VERSION` |
| `readme.txt` | `Stable tag` |
| `composer.json` | `"version"` |
| `CHANGELOG.md` | `## [x.y.z]` heading and date |

Also update WordPress compatibility in:

| Location | Fields |
|----------|--------|
| `metabox-schema.php` | `Requires at least`, `Tested up to`, `Requires PHP` |
| `readme.txt` | `Requires at least`, `Tested up to`, `Requires PHP` |
| `phpcs.xml` | `minimum_supported_wp_version` (must match **Requires at least**) |
| `README.md` | **Requirements** section |

`Media_Field` uses `METABOX_SCHEMA_VERSION` for script/style cache busting, then falls back to `composer.json` `version`, then `1.0.0`.

**Tested up to:** use the latest stable WordPress **major.minor** branch (e.g. `7.1` when core is at 7.1.3), not the patch version. Check [wordpress.org/download/releases](https://wordpress.org/download/releases/).
