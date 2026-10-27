---
name: validator
description: Validate and sanitize form data against schema definitions. Use when validating POST data, sanitizing user input, checking field requirements, or applying custom validation rules.
license: MIT
location: includes/core/class-validator.php
metadata:
    author: code-soup
    version: "1.3"
    package: codesoup/metabox-schema
---

# Schema Validator

Validate and sanitize form data against schema definitions for WordPress forms.

## When to Use

- Validating form submissions
- Sanitizing user input
- Checking data integrity
- Generating validation error messages
- Processing WordPress form data

## Basic Usage

See `examples/basic-validation.md` for complete example.

## Public Methods

- `validate( array $data, array $schema ): array` - Validate and sanitize data
- `get_errors(): array` - Get validation errors (field_name => error_message)
- `has_errors(): bool` - Check if validation has errors

## Validation Rules

| Rule          | Type         | Description                                        |
| ------------- | ------------ | -------------------------------------------------- |
| `required`    | bool         | Field is required                                  |
| `min`         | int\|float   | Min length (text), value (number), or selection count (`checkbox_group`) |
| `max`         | int\|float   | Max length (text), value (number), or selection count (`checkbox_group`) |
| `pattern`     | string       | Regular expression pattern                         |
| `format`      | string       | Format validation: `email`, `url`, `date` only   |
| `date_format` | string       | PHP date format when `format` is `date` (default: `Y-m-d`) |
| `validate`    | callable     | Custom validation callback                         |

## Option allowlists

When `options` is set, submitted values must be allowed keys:

- **select** — single value
- **radio** — single value
- **checkbox_group** — each selected value in the POST array

Nested optgroups in `select` options are supported (same as rendering).

## Skipped field types

No validation or sanitization pass for schema rows with type:

- `html`
- `label`
- `help`

## Format Validation

- **email** — WordPress `is_email()`
- **url** — WordPress `wp_http_validate_url()`
- **date** — strict `DateTime::createFromFormat()` using `date_format` or `Constants::DEFAULT_DATE_FORMAT`

## Custom Validation

See `examples/custom-validation.md` for custom callbacks and error messages.

## Sanitization

Order: **sanitize first**, then validate.

By field type:

- **number** — numeric; integers preserved when exact
- **email** — `sanitize_email()`
- **url** — `esc_url_raw()`
- **textarea** — `sanitize_textarea_field()`
- **arrays** (e.g. checkbox_group) — `array_map( 'sanitize_text_field', … )`
- **default** — `sanitize_text_field()`; custom types may use filter `codesoup_metabox_schema_sanitize_default`

Custom sanitization via schema `sanitize` (single callable or array of callables).

## Empty values

Treated as empty: `null`, `''`, and empty arrays `[]`.

Optional fields with empty input resolve schema **`default`** (including callables). Required fields record an error.

## Important Notes

- `0` and `'0'` are **not** empty
- Custom validators return `true` or an error message string
- Extending: protected method `has_validation_errors()` (not `validate_value()`)
- Min/max rule values must be int or float
- POST data is usually keyed by field name inside `$_POST[ $form_prefix ]`, not raw `$_POST`

## See Also

- [field-renderer](/skills/field-renderer/SKILL.md) - Render forms from schemas
- [schema-definition](/skills/schema-definition/SKILL.md) - Define field schemas
- [utilities](/skills/utilities/SKILL.md) - Use Constants and utility classes
