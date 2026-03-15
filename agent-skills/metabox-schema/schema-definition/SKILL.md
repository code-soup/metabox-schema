---
name: schema-definition
description: Define field schemas with validation and sanitization rules for WordPress forms.
license: MIT
tags:
  - wordpress
  - forms
  - schema
  - validation
metadata:
  author: code-soup
  version: "1.0"
  package: codesoup/metabox-schema
---

# Schema Definition

Define and structure field schemas correctly for the CodeSoup Metabox Schema package.

## When to Use

- Creating new field schemas
- Defining form structures
- Configuring field validation
- Setting up field properties
- Working with entity value resolution

## When NOT to Use

- Simple one-off forms without validation
- Non-WordPress projects
- Forms that don't need reusable configuration

## Examples

- `examples/basic-schema.php` - Basic field schema structure
- `examples/validation-example.php` - Validation rules examples
- `examples/value-resolution.php` - Value resolution patterns

## Basic Schema Structure

See `examples/basic-schema.php` for complete example.

## Field Properties Reference

### Required Properties

- **type** (string) - Field type: text, email, url, number, date, password, tel, color, range, textarea, select, wp_editor, media, html, label, help

### Common Properties

- **label** (string) - Field label text
- **value** (mixed/callable/string) - Current value, callable, or entity method name
- **default** (mixed/callable) - Default value or callback (used when value is empty)
- **attributes** (array) - HTML attributes (class, placeholder, maxlength, data-*, etc.)
  - Note: `id` and `name` are auto-generated and ignored if provided in attributes
  - Auto-generated ID format: `form-prefix-field-name`
- **validation** (array) - Validation rules
- **errors** (array) - Custom error messages for validation rules
- **sanitize** (callable/array) - Sanitization callback(s)
- **help** (string) - Help text displayed below field
- **wrapper** (string) - Wrapper element: 'p' (default), 'div', 'span', '' (no wrapper)

### Field-Specific Properties

- **options** (array) - For select fields (key => label)
- **rows** (int) - For textarea/wp_editor (default: 5)
- **grid** (string) - Grid layout: 'start' or 'end'
- **editor_settings** (array) - For wp_editor (WordPress editor config)
- **content** (string/callable) - For html type: HTML content to display
- **button_text** (string) - For media type: Button text (default: 'Select Media')
- **media_type** (string) - For media type: Filter by type (image, video, audio)
- **preview_size** (string) - For media type: WordPress image size for preview

## Value Resolution

See `examples/value-resolution.php` for complete examples of:
- Static values
- Entity method resolution
- Callable functions
- Default values

## Examples

All code examples are in the `examples/` folder:
- `examples/basic-schema.php` - Basic field structure
- `examples/validation-example.php` - Validation rules
- `examples/value-resolution.php` - Value resolution patterns

## See Also

- [field-renderer](../field-renderer/SKILL.md) - Render forms from schemas
- [validator](../validator/SKILL.md) - Validate and sanitize user input
- [template-creator](../template-creator/SKILL.md) - Create custom templates
- [utilities](../utilities/SKILL.md) - Use Constants and utility classes

