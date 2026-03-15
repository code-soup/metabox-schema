---
name: schema-validator
description: Validate and sanitize form data against schema definitions.
license: MIT
tags:
  - wordpress
  - validation
  - forms
  - sanitization
metadata:
    author: code-soup
    version: "1.0"
    package: codesoup/metabox-schema
---

# Schema Validator

Validate and sanitize form data against schema definitions using the CodeSoup Metabox Schema Validator class.

## When to Use

- Validating form submissions
- Sanitizing user input
- Checking data integrity
- Generating validation error messages
- Processing WordPress form data

## Basic Usage

See `examples/basic-validation.php` for complete example.

## Public Methods

- `validate( array $data, array $schema ): array` - Validate and sanitize data
- `get_errors(): array` - Get validation errors (field_name => error_message)
- `has_errors(): bool` - Check if validation has errors

## Validation Rules

| Rule          | Type     | Description                                        |
| ------------- | -------- | -------------------------------------------------- |
| `required`    | bool     | Field is required                                  |
| `min`         | int      | Minimum length (text) or value (number)            |
| `max`         | int      | Maximum length (text) or value (number)            |
| `pattern`     | string   | Regular expression pattern                         |
| `format`      | string   | Format validation: 'email', 'url', 'date'          |
| `date_format` | string   | Date format for date validation (default: 'Y-m-d') |
| `validate`    | callable | Custom validation callback                         |

## Format Validation

- **Email** - Uses WordPress `is_email()`
- **URL** - Uses WordPress `wp_http_validate_url()`
- **Date** - Uses strict `DateTime::createFromFormat()`

## Custom Validation

See `examples/custom-validation.php` for examples of:
- Custom validation callbacks
- Custom error messages
- Complex validation logic

## Sanitization

Automatic sanitization based on field type:

- **email** - `sanitize_email()`
- **url** - `esc_url_raw()`
- **textarea** - `sanitize_textarea_field()`
- **text/other** - `sanitize_text_field()`

Custom sanitization via `sanitize` config (single callback or array of callbacks).

## Important Notes

- Validation happens before sanitization
- Date validation is strict (exact format match required)
- Custom validators should return `true` or error message string
- Uses WordPress sanitization functions
- Field names auto-formatted for error messages (via String_Formatter)

## See Also

- [Schema Field Renderer](../schema-field-renderer/SKILL.md) for rendering
- [Schema Definition](../schema-definition/SKILL.md) for schema structure
- [Utility Classes](../utility-classes/SKILL.md) for String_Formatter, Value_Resolver, Constants
