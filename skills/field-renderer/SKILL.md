---
name: field-renderer-schema
description: Render HTML form fields from schema definitions for WordPress forms.
license: MIT
author: code-soup
metadata:
    author: code-soup
    version: "1.0"
    package: codesoup/metabox-schema
---

# Schema Field Renderer

Render HTML form fields from schema definitions using the CodeSoup Metabox Schema Renderer class.

## When to Use

- Rendering form fields from schema
- Generating HTML for WordPress metaboxes
- Creating custom forms with validation
- Working with entity objects or value arrays

## Basic Usage

## Examples

See `examples/` folder for complete examples:

- `examples/basic-render.md` - Basic rendering
- `examples/custom-template-render.md` - Custom template override

## Available Field Types

- **text, email, url, number, date, password, tel, color, range** - Input fields
- **textarea** - Multi-line text
- **select** - Dropdown with options
- **wp_editor** - WordPress rich text editor
- **media** - WordPress media library picker
- **html** - HTML content display
- **label** - Field labels
- **help** - Help text

## Field Schema Properties

- `type` - Field type (required)
- `label` - Field label
- `value` - Static value, callable, or entity method name
- `default` - Default value or callback
- `attributes` - HTML attributes (placeholder, class, etc.)
- `validation` - Validation rules
- `help` - Help text below field
- `wrapper` - Wrapper element (p, div, span, or empty)
- `options` - For select fields (key => label array)
- `rows` - For textarea/wp_editor (default: 5)
- `editor_settings` - For wp_editor (WordPress editor config)
- `content` - For html type (string or callable)
- `button_text` - For media type (default: 'Select Media')
- `media_type` - For media type (image, video, audio)
- `preview_size` - For media type (WordPress image size)

## Custom Templates

See `examples/custom-template-render.md` for template override example.

Template structure:

- `input/template.md` - All input types
- `textarea/template.md` - Textarea fields
- `select/template.md` - Select dropdowns
- `wp-editor/template.md` - WordPress editor
- `media/template.md` - Media library
- `html/template.md` - HTML content
- `label/template.md` - Field labels
- `help/template.md` - Help text

## Available Methods in Templates

Inside templates, `$this` refers to Field object:

- `$this->get_field_id()` - Sanitized field ID
- `$this->get_field_name()` - Sanitized field name
- `$this->get_label()` - Sanitized label
- `$this->get_escaped_value()` - Escaped value for inputs
- `$this->get_escaped_textarea_value()` - Escaped value for textareas
- `$this->get_type()` - Sanitized field type
- `$this->is_required()` - Boolean
- `$this->get_required_attr()` - ' required' or empty
- `$this->get_attributes_string()` - Sanitized attributes
- `$this->get_help()` - Help text
- `$this->get_rows()` - Textarea rows
- `$this->get_options()` - Sanitized select options

- `$this->get_editor_settings()` - WP editor settings

## Important Notes

- Schema configuration is sanitized at construction
- User values are escaped via getter methods
- Templates output clean data without additional escaping
- Requires WordPress functions (esc_attr, sanitize_key, etc.)
- You must wrap output in `<form>` tags yourself
- Package only renders fields, doesn't handle submission

## Internal Architecture

The Field class uses several utility components:

**Value_Resolver Trait:**

- Resolves callable values (closures, functions)
- Resolves entity method calls
- Ensures consistent value resolution

**Config_Sanitizer:**

- Sanitizes configuration at construction
- Validates wrapper tags
- Sanitizes options and attributes

**Constants:**

- Provides default values (DEFAULT_TYPE, DEFAULT_WRAPPER, DEFAULT_ROWS)
- Defines valid wrapper tags
- Defines special field types

**When Extending Field:**

```php
use CodeSoup\MetaboxSchema\Field;
use CodeSoup\MetaboxSchema\Constants;

class Custom_Field extends Field {
    protected function resolve_value(): mixed {
        // Use trait methods
        $value = $this->resolve_callable($this->config['value']);
        return $value;
    }

    public function get_type(): string {
        // Use constants
        return $this->config['type'] ?? Constants::DEFAULT_TYPE;
    }
}
```

## See Also

- [validator](../validator/SKILL.md) - Validate and sanitize user input
- [schema-definition](../schema-definition/SKILL.md) - Define field schemas
- [template-creator](../template-creator/SKILL.md) - Create custom templates
- [utilities](../utilities/SKILL.md) - Use Constants and utility classes
