---
name: field-renderer
description: Render HTML form fields from schema definitions for WordPress forms. Use when rendering forms from schemas, generating metabox HTML, or working with entity objects and value arrays.
license: MIT
location: includes/core/class-renderer.php
metadata:
    author: code-soup
    version: "1.3"
    package: codesoup/metabox-schema
---

# Schema Field Renderer

Render HTML form fields from schema definitions for WordPress forms.

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
- `help_position` - Help position: 'before' or 'after' (default: 'after')
- `wrapper` - Wrapper element (p, div, span, or empty)
- `wrapper_class` - CSS classes for wrapper (string or array)
- `required_class` - CSS class for required fields (default: 'has-required')
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
- `$this->get_wrapper_id()` - Wrapper ID (field-id + '-wrap')
- `$this->get_wrapper_class()` - Wrapper CSS classes (includes required class if applicable)
- `$this->get_label()` - Sanitized label
- `$this->get_escaped_value()` - Escaped value for inputs
- `$this->get_escaped_textarea_value()` - Escaped value for textareas
- `$this->get_type()` - Sanitized field type
- `$this->is_required()` - Boolean
- `$this->get_required_attr()` - ' required' or empty
- `$this->get_required_class()` - Required CSS class name
- `$this->get_attributes_string()` - Sanitized attributes
- `$this->get_help()` - Help text
- `$this->get_help_position()` - Help position ('before' or 'after')
- `$this->get_rows()` - Textarea rows
- `$this->get_options()` - Sanitized select options
- `$this->get_editor_settings()` - WP editor settings

## Wrapper Rendering

Each field is wrapped in a configurable element (default: `div`):

- **Wrapper ID**: Auto-generated as `{field-id}-wrap` (e.g., `form-email-wrap`)
- **Wrapper Classes**: Combines custom classes with automatic required class
- **Required Class**: Automatically added when `validation.required = true` (default: `has-required`)

**Global Configuration:**

```php
Renderer::render([
    'schema' => $schema,
    'form_prefix' => 'form',
    'default_required_class' => 'is-required',  // Override default required class
    'default_help_position' => 'before',        // Override default help position
]);
```

**Field-Level Filters:**

```php
// Filter required class for specific fields
add_filter('codesoup_metabox_field_required_class', function($class, $field_name, $config) {
    if ($field_name === 'email') {
        return 'mandatory';
    }
    return $class;
}, 10, 3);

// Filter help position based on field type
add_filter('codesoup_metabox_field_help_position', function($position, $field_name, $config) {
    if ($config['type'] === 'textarea') {
        return 'before';  // Always show help before textareas
    }
    return $position;
}, 10, 3);
```

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

- [validator](/skills/validator/SKILL.md) - Validate and sanitize user input
- [schema-definition](/skills/schema-definition/SKILL.md) - Define field schemas
- [template-creator](/skills/template-creator/SKILL.md) - Create custom templates
- [utilities](/skills/utilities/SKILL.md) - Use Constants and utility classes
