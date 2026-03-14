---
name: custom-template-creator
description: Create custom field templates for CodeSoup Metabox Schema renderer. Use when customizing field appearance, creating new field types, overriding default templates, or when user mentions template customization, field styling, or custom HTML output.
license: MIT
metadata:
  author: codesoup
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

### Custom Input Template

```php
<?php
/**
 * Custom Input Template
 *
 * @var \CodeSoup\MetaboxSchema\Fields\Input_Field $this
 */
// templates/input/template.php
defined( 'ABSPATH' ) || exit;

printf(
    '<div class="form-field"><input id="%s" name="%s" type="%s" value="%s"%s%s class="custom-input" /></div>',
    $this->get_field_id(),
    $this->get_field_name(),
    $this->get_type(),
    $this->get_escaped_value(),
    $this->get_required_attr(),
    $this->get_attributes_string()
);
```

### Custom Textarea Template

```php
<?php
/**
 * Custom Textarea Template
 *
 * @var \CodeSoup\MetaboxSchema\Fields\Textarea_Field $this
 */
// templates/textarea/template.php
defined( 'ABSPATH' ) || exit;

printf(
    '<textarea id="%s" name="%s" rows="%s"%s%s class="custom-textarea">%s</textarea>',
    $this->get_field_id(),
    $this->get_field_name(),
    $this->get_rows(),
    $this->get_required_attr(),
    $this->get_attributes_string(),
    $this->get_escaped_textarea_value()
);
```

### Custom Select Template

```php
<?php
// templates/select.php
defined( 'ABSPATH' ) || exit;

$options_html = '';
foreach ( $this->get_options() as $option_value => $option_label ) {
    $options_html .= sprintf(
        '<option value="%s"%s>%s</option>',
        $option_value,
        selected( $this->get_value(), $option_value, false ),
        $option_label
    );
}

printf(
    '<select id="%s" name="%s"%s%s>%s</select>',
    $this->get_field_id(),
    $this->get_field_name(),
    $this->get_required_attr(),
    $this->get_attributes_string(),
    $options_html
);
```

### Custom Label Template

```php
<?php
// templates/label.php
defined( 'ABSPATH' ) || exit;

$label = $this->get_label();

if ( $label ) {
    printf(
        '<label for="%s" class="custom-label">%s</label>',
        $this->get_field_id(),
        $label
    );
}
```

## Important Notes

- All data from Field methods is already sanitized/escaped
- Templates should NOT add additional escaping
- Use `get_escaped_value()` for inputs, `get_escaped_textarea_value()` for textareas
- Schema data is sanitized at construction
- Templates just output clean data
- Requires WordPress functions to be available
- Templates are included via PHP `include`, so `$this` context is available

## Override Single Field

Override template for specific field only:

```php
'bio' => [
    'type' => 'textarea',
    'label' => 'Biography',
    'template_path' => __DIR__ . '/templates/featured-textarea.php'
]
```

## See Also

- [Schema Field Renderer](../schema-field-renderer/SKILL.md) for rendering
- [Schema Definition](../schema-definition/SKILL.md) for schema structure
- [Utility Classes](../utility-classes/SKILL.md) for Constants and Config_Sanitizer

