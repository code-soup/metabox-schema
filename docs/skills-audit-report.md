# Agent skills audit report (1.3.0)

Audit date: 2026-10-27 (Bangkok). Compared skills under `skills/` to `includes/` on the 1.3.0 release line.

## Summary

| Skill | Issues found | Severity |
|-------|----------------|----------|
| schema-definition | Missing field types; wrong default wrapper; wrong field-name rules; date validation example | High |
| field-renderer | Missing types; `template.md` vs `template.php`; wrong base class in extend example | High |
| validator | Unsupported `ip` format; missing 1.3 rules; outdated metadata version | High |
| template-creator | `template.md` paths; `get_field_id()` described as escaped | Medium |
| utilities | `SKIP_VALIDATION_TYPES` incomplete | Medium |
| value-resolver example | Wrong resolution order (callable before entity) | High |
| validation-example | `date_format` without `format => date` | High |
| custom-field examples | Unused `Field_Factory` import | Low |

Corrections are in PR branch `cursor/skills-audit-fixes-e7c4`.

## Fact-check notes

- **Field names / form prefix:** Must match WordPress `sanitize_key()` (lowercase letters, numbers, underscores). Hyphens are stripped and will throw `InvalidArgumentException`.
- **Default wrapper:** `Constants::DEFAULT_WRAPPER` is `div`, not `p`.
- **Templates:** Files are `template.php`, not `template.md` (markdown examples in skills use `.md` filenames only for documentation copies).
- **Rendering value resolution:** `resolve_entity_method()` runs before `resolve_callable()` on the field value source.
- **Validator formats:** Supported `validation.format` values are `email`, `url`, and `date` only.
- **Option allowlists:** `select`, `radio`, and `checkbox_group` validate against `options`.
- **Display types:** `html`, `label`, and `help` skip validation.
