---
name: custom-field-registration
description: Register custom field types with Renderer instances. Use when creating specialized field types, adding custom behavior to fields, building reusable field components, or overriding built-in field types.
license: MIT
location: includes/core/class-renderer.php
metadata:
    author: code-soup
    version: "1.1"
    package: codesoup/metabox-schema
---

# Custom Field Type Registration

## Purpose

Register custom field types with Renderer instances. Extend built-in field types with specialized implementations while avoiding conflicts in multi-plugin environments.

## When to Use

- Creating specialized field types (color picker, date range, etc.)
- Adding custom behavior to existing field types
- Building reusable field components
- Need plugin-isolated field registrations

## When NOT to Use

- Simple template customization (use template override instead)
- One-off field modifications (use field config instead)
- Non-field functionality

## Examples

- `examples/basic-custom-field.md` - Basic custom field implementation
- `examples/color-picker-field.md` - Complete color picker example
- `examples/override-builtin.md` - Override built-in field type

## How It Works

1. Create a class that extends `Abstract_Field`
2. Implement `get_template_name()` method
3. Add custom methods for field-specific configuration
4. Create Renderer instance and register field type
5. Use the custom type in your schema

## Registration

```php
use CodeSoup\MetaboxSchema\Renderer;

$renderer = new Renderer();
$renderer->register_field_type( 'color_picker', Color_Picker_Field::class );

$renderer->render_fields([
    'schema' => [
        'brand_color' => [ 'type' => 'color_picker', 'label' => 'Brand Color' ]
    ],
    'form_prefix' => 'settings'
]);
```

**Benefits:**
- ✅ No conflicts between plugins
- ✅ Each renderer has isolated registry
- ✅ Safe for multi-plugin environments

## Validation

The registration method validates:

- Field type name is not empty
- Class exists
- Class extends `Abstract_Field`

## Resolution Order

1. Instance custom types (highest priority)
2. Built-in field types
3. Exception if type not found

## Requirements

- Class must extend `CodeSoup\MetaboxSchema\Abstract_Field`
- Must implement `get_template_name(): string` method
- Register before rendering

## Template Structure

Create template in your custom location:

```
templates/
└── color-picker/
    └── template.md
```

## Common Use Cases

### Custom Field Type

Create new field type with custom behavior and template.

### Override Built-in Type

Replace built-in field type with custom implementation.

### Extend Existing Type

Extend existing field class to add functionality.

## Related Skills

- [schema-definition](/skills/schema-definition/SKILL.md) - Define field schemas
- [template-creator](/skills/template-creator/SKILL.md) - Create custom templates
- [field-renderer](/skills/field-renderer/SKILL.md) - Render forms

## Examples

See `examples/` folder for complete working examples:
- `basic-custom-field.md` - Simple rating field
- `color-picker-field.md` - Advanced color picker with options
- `override-builtin.md` - Override built-in text field
