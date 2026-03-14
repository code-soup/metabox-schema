# CodeSoup Metabox Schema

Drop-in schema-based form field renderer and validator. Define your fields once, render forms and validate data.

## What This Package Does

1. **Render form fields** from a schema definition
2. **Validate submitted data** against the same schema

This is **not** a complete form solution. You handle form submission, data persistence, and integration.

## Installation

```bash
composer require codesoup/metabox-schema
```

## Quick Start

```php
use CodeSoup\MetaboxSchema\Renderer;
use CodeSoup\MetaboxSchema\Validator;

// 1. Define schema
$schema = [
    'email' => [
        'type' => 'email',
        'label' => 'Email',
        'validation' => [ 'required' => true ]
    ]
];

// 2. Render fields
Renderer::render([
    'schema' => $schema,
    'form_prefix' => 'contact'
]);

// 3. Validate data
$validator = new Validator();
$validated_data = $validator->validate( $_POST['contact'], $schema );

if ( $validator->has_errors() ) {
    $errors = $validator->get_errors();
}
```

## Examples

See the `examples/` directory for complete working examples:

- **`basic-usage.php`** - Complete form rendering and validation
- **`custom-templates.php`** - Override default field templates
- **`extend-validator.php`** - Add custom validation rules
- **`extend-renderer.php`** - Customize rendering behavior

## Core Concepts

### Schema

Array defining field structure, validation rules, and configuration:

```php
$schema = [
    'field_name' => [
        'type' => 'text',
        'label' => 'Field Label',
        'value' => 'default',
        'validation' => [
            'required' => true,
            'min' => 3,
            'max' => 50,
            'pattern' => '^[a-zA-Z0-9]+$'
        ],
        'errors' => [
            'required' => 'Please enter a value',
            'min' => 'Value is too short'
        ],
        'attributes' => [
            'class' => 'custom-class another-class',
            'placeholder' => 'Enter value',
            'maxlength' => 50,
            'data-custom' => 'value',
            'data-validate' => 'true'
        ],
        'help' => 'Help text'
    ]
];
```

### Supported Field Types

**Input Fields:**
- `text`, `email`, `url`, `number`, `date`, `password`, `tel`, `color`, `range`

**Content Fields:**
- `textarea` - Multi-line text input
- `select` - Dropdown with options
- `wp_editor` - WordPress rich text editor
- `media` - WordPress media library picker

**Display Fields:**
- `html` - HTML content display
- `label` - Field labels
- `help` - Help text

### Validation Rules

- `required` - Field must have value
- `min` / `max` - Min/max value or length
- `format` - Format validation (email, url, date)
- `pattern` - Regex pattern

Custom error messages via `'errors'` array (see schema example above).

### Field IDs and Attributes

Each field automatically gets an ID in format `form-prefix-field-name`. Use `attributes` array for CSS classes and data attributes. Note: `id` and `name` in attributes are ignored (auto-generated).

### Value Resolution

Fields can get values from:
1. Static `'value'` in schema
2. Callable `'value' => fn() => get_option('key')`
3. Entity methods `'value' => 'get_email'` (calls `$entity->get_email()`)
4. Values array passed to Renderer

### Custom Templates

Templates are organized in subdirectories by field type:

```
templates/
├── input/
│   └── template.php
├── textarea/
│   └── template.php
├── select/
│   └── template.php
└── ...
```

Override templates globally or per-field:

```php
// Global override
Renderer::render([
    'schema' => $schema,
    'template_base' => __DIR__ . '/templates'
]);

// Single field override
$schema['bio']['template_path'] = __DIR__ . '/templates/textarea/custom.php';
```

See `examples/custom-templates.php` and `examples/templates/` for details.

## API Reference

### Renderer

```php
Renderer::render([
    'schema' => $schema,        // Required
    'form_prefix' => 'my_form', // Required
    'entity' => $object,        // Optional
    'values' => $array,         // Optional
    'template_base' => $path    // Optional
]);
```

### Validator

```php
$validator = new Validator();
$validated_data = $validator->validate( $data, $schema );

if ( $validator->has_errors() ) {
    $errors = $validator->get_errors();
    // Returns: [ 'email' => 'Email is required', 'age' => 'Age must be at least 18' ]
}
```

### Field Methods (Available in Templates)

**Common Methods (All Fields):**
- `get_field_id()`, `get_field_name()`, `get_type()`, `get_label()`
- `get_value()`, `get_escaped_value()`, `get_escaped_textarea_value()`
- `is_required()`, `get_required_attr()`, `get_attributes_string()`
- `get_help()`, `get_wrapper()`

**Field-Specific Methods:**
- `get_rows()` - Textarea, WP_Editor
- `get_options()` - Select
- `get_editor_settings()` - WP_Editor
- `get_content()` - HTML
- `get_button_text()`, `get_media_type()`, `get_preview_size()` - Media

See `examples/templates/` for template usage examples.

## Agent Skills

This package includes AI agent skills for code generation assistance. Located in `agent-skills/`:

- **`schema-definition/`** - Generate schema definitions
- **`schema-field-renderer/`** - Implement field rendering
- **`custom-template-creator/`** - Create custom field templates

Each skill includes detailed documentation and usage examples for AI-assisted development.

## Extending

### Custom Validator

```php
class Custom_Validator extends Validator {
    protected function validate_value( $value, $context ) {
        // Custom validation logic
        return parent::validate_value( $value, $context );
    }
}
```

See `examples/extend-validator.php` for complete example.

### Custom Renderer

```php
class Custom_Renderer extends Renderer {
    protected function render_field( $field_name, $field_config ) {
        // Custom rendering logic
        parent::render_field( $field_name, $field_config );
    }
}
```

See `examples/extend-renderer.php` for complete example.

## Architecture

- **Renderer** - Static `render()` method, extensible protected methods
- **Validator** - Instance-based, extensible validation rules
- **Field_Factory** - Creates field instances from configuration
- **Abstract_Field** - Base class for all field types
- **Field Classes** - Input_Field, Textarea_Field, Select_Field, Media_Field, WP_Editor_Field, HTML_Field, Label_Field, Help_Field
- **Value_Resolver** - Trait for resolving values from multiple sources
- **String_Formatter** - Utility for string transformations
- **Config_Sanitizer** - Sanitizes and validates configuration arrays
- **Constants** - Default values and configuration constants

## License

MIT License - See LICENSE file for details.

## Requirements

- PHP 8.1+
- WordPress 5.0+ (for escaping functions, wp_editor, and media library)

