---
name: template-creator
description: Create custom field templates for WordPress form fields. Use when customizing field HTML output, overriding default templates, integrating with CSS frameworks, or creating new field types.
license: MIT
location: includes/fields/
metadata:
  author: code-soup
  version: "1.0"
  package: codesoup/metabox-schema
---

# Custom Template Creator

Create custom field templates for WordPress form fields.

## When to Use

- Customizing field HTML output
- Creating new field types
- Overriding default templates
- Adding custom styling or markup
- Integrating with CSS frameworks

## Template Structure

Templates are PHP files that output HTML. Inside templates, `$this` refers to the specific field class instance (Input_Field, Textarea_Field, etc.).

### Required Template Files

- `input/template.md` - All input types (text, email, number, etc.)
- `textarea/template.md` - Textarea fields
- `select/template.md` - Select dropdowns
- `wp-editor/template.md` - WordPress editor
- `media/template.md` - Media library fields
- `html/template.md` - HTML content
- `label/template.md` - Field labels
- `help/template.md` - Help text

## Template Directory Structure

```
templates/
├── input/
│   └── template.md
├── textarea/
│   └── template.md
├── select/
│   └── template.md
├── wp-editor/
│   └── template.md
├── media/
│   └── template.md
├── html/
│   └── template.md
├── label/
│   └── template.md
└── help/
    └── template.md
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

- `examples/input-template.md` - Custom input field template
- `examples/textarea-template.md` - Custom textarea field template

Templates have access to the field instance via `$this` and can call any public method.

## Important Notes

- All data from Field methods is already sanitized/escaped
- Templates should NOT add additional escaping
- Use `get_escaped_value()` for inputs, `get_escaped_textarea_value()` for textareas
- Templates are included via PHP `include`, so `$this` context is available

## See Also

- [field-renderer](/skills/field-renderer/SKILL.md) - Render forms from schemas
- [schema-definition](/skills/schema-definition/SKILL.md) - Define field schemas
- [utilities](/skills/utilities/SKILL.md) - Use Constants and utility classes

