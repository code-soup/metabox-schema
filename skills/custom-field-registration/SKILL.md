---
name: metabox-custom-field-registration
description: Register custom field types with Field_Factory
version: 1.0.0
license: MIT
tags:
    - wordpress
    - forms
    - custom-fields
    - extensibility
author: code-soup
---

# Custom Field Type Registration

## Purpose

Register custom field types with the Metabox Schema package. Extend built-in field types with specialized implementations.

## When to Use

- Creating specialized field types (color picker, date range, etc.)
- Adding custom behavior to existing field types
- Building reusable field components
- Overriding built-in field types

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
4. Register with `Field_Factory::register_field_type()`
5. Use the custom type in your schema

## Registration

```php
use CodeSoup\MetaboxSchema\Field_Factory;

Field_Factory::register_field_type( 'color_picker', Color_Picker_Field::class );
```

## Validation

The registration method validates:

- Field type name is not empty
- Class exists
- Class extends `Abstract_Field`
- Warns if overriding existing custom type

## Resolution Order

1. Custom registered types (highest priority)
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

## References

- [CUSTOM-FIELD-TYPES.md](../../CUSTOM-FIELD-TYPES.md) - Full documentation
- [examples/register-custom-field-type.php](../../examples/register-custom-field-type.php) - Working example
