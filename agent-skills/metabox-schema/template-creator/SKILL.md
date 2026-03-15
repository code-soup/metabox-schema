---
name: custom-template-creator
description: Create custom field templates for CodeSoup Metabox Schema renderer. Use when customizing field appearance, creating new field types, overriding default templates, or when user mentions template customization, field styling, or custom HTML output.
license: MIT
metadata:
  author: code-soup
  version: "1.0"
  package: codesoup/metabox-schema
---

# Custom Template Creator

Create custom field templates for the CodeSoup Metabox Schema renderer.

## When to Use

- Customizing field HTML output
- Creating new field types
- Overriding default templates
- Adding custom styling or markup
- Integrating with CSS frameworks

## Template Structure

Templates are PHP files that output HTML. Inside templates, `$this` refers to the specific field class instance (Input_Field, Textarea_Field, etc.).

### Required Template Files

- `input/template.php` - All input types (text, email, number, etc.)
- `textarea/template.php` - Textarea fields
- `select/template.php` - Select dropdowns
- `wp-editor/template.php` - WordPress editor
- `media/template.php` - Media library fields
- `html/template.php` - HTML content
- `label/template.php` - Field labels
- `help/template.php` - Help text

## Template Directory Structure

```
templates/
├── input/
│   └── template.php
├── textarea/
│   └── template.php
├── select/
│   └── template.php
├── wp-editor/
│   └── template.php
├── media/
│   └── template.php
├── html/
│   └── template.php
├── label/
│   └── template.php
└── help/
    └── template.php
```

## Using Custom Templates

```php
Renderer::render([
    'schema' => $schema,
    'entity' => $entity,
    'form_prefix' => 'custom',
    'template_base' => __DIR__ . '/templates'
]);
```

## Available Field Methods

All methods return sanitized/escaped data ready for output:

### Field Identification
- `$this->get_field_id()` - Sanitized field ID
- `$this->get_field_name()` - Sanitized field name attribute
- `$this->get_type()` - Sanitized field type

### Field Content
- `$this->get_label()` - Sanitized label text
- `$this->get_escaped_value()` - Escaped value for input/select
- `$this->get_escaped_textarea_value()` - Escaped value for textarea
- `$this->get_help()` - Help text (sanitized with wp_kses)

### Field Properties
- `$this->is_required()` - Boolean
- `$this->get_required_attr()` - Returns ' required' or empty string
- `$this->get_attributes_string()` - Sanitized HTML attributes
- `$this->get_rows()` - Textarea rows (integer)

### Select/Options
- `$this->get_options()` - Sanitized options array (key => label)

### WordPress Editor (WP_Editor_Field)
- `$this->get_editor_settings()` - Editor settings array
- `$this->get_rows()` - Number of rows

### Media Fields (Media_Field)
- `$this->get_button_text()` - Button text
- `$this->get_change_button_text()` - Change button text
- `$this->get_remove_button_text()` - Remove button text
- `$this->get_media_type()` - Media type filter
- `$this->get_media_library_title()` - Modal title
- `$this->get_media_library_button()` - Modal button text
- `$this->get_preview_size()` - Preview image size

### HTML Fields (HTML_Field)
- `$this->get_content()` - HTML content (raw, sanitized in template)

## Template Examples

See `examples/` folder for complete examples:

- `examples/input-template.php` - Custom input field template
- `examples/textarea-template.php` - Custom textarea field template

Templates have access to the field instance via `$this` and can call any public method.

## Important Notes

- All data from Field methods is already sanitized/escaped
- Templates should NOT add additional escaping
- Use `get_escaped_value()` for inputs, `get_escaped_textarea_value()` for textareas
- Templates are included via PHP `include`, so `$this` context is available

## See Also

- [field-renderer](../field-renderer/SKILL.md) - Render forms from schemas
- [schema-definition](../schema-definition/SKILL.md) - Define field schemas
- [utilities](../utilities/SKILL.md) - Use Constants and utility classes

