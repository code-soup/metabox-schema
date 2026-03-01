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
- **`override-single-field.php`** - Custom template for specific field
- **`extend-validator.php`** - Add custom validation rules
- **`extend-renderer.php`** - Customize rendering behavior
- **`utility-classes.php`** - Using Constants, String_Formatter, Config_Sanitizer
- **`value-resolver-trait.php`** - Using Value_Resolver trait

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

- `text`, `email`, `url`, `number`, `date`
- `textarea`, `select`, `wysiwyg`
- `heading` (non-input display element)

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

Override templates globally or per-field:

```php
// Global override
Renderer::render([
    'schema' => $schema,
    'template_base' => __DIR__ . '/templates'
]);

// Single field override
$schema['bio']['template_path'] = __DIR__ . '/custom-textarea.php';
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

- `get_field_id()`, `get_field_name()`, `get_type()`, `get_label()`
- `get_value()`, `get_escaped_value()`, `get_escaped_textarea_value()`
- `is_required()`, `get_required_attr()`, `get_attributes_string()`
- `get_rows()`, `get_options()`, `get_help()`, `get_wrapper()`, `get_heading_tag()`

See `examples/templates/` for template usage examples.

## Agent Skills

This package includes AI agent skills for code generation assistance. Located in `agent-skills/`:

- **`schema-definition/`** - Generate schema definitions
- **`schema-field-renderer/`** - Implement field rendering
- **`schema-validator/`** - Implement validation logic
- **`custom-template-creator/`** - Create custom field templates
- **`utility-classes/`** - Use Constants, String_Formatter, Config_Sanitizer

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
- **Field** - Handles individual field rendering and value escaping
- **Value_Resolver** - Trait for resolving values from multiple sources
- **String_Formatter** - Utility for string transformations
- **Config_Sanitizer** - Sanitizes and validates configuration arrays
- **Constants** - Field types, validation rules, error messages

## License

MIT License - See LICENSE file for details.

## Requirements

- PHP 7.4+
- WordPress 5.0+ (for escaping functions and wp_editor)

