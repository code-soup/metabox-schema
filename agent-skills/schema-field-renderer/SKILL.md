---
name: schema-field-renderer
description: Render HTML form fields from schema definitions using CodeSoup Metabox Schema package. Use when working with form rendering, field schemas, HTML output, WordPress forms, or when user mentions rendering fields, schemas, or form generation.
license: MIT
metadata:
  author: codesoup
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

### Render with Values Array

```php
use CodeSoup\MetaboxSchema\Renderer;

$schema = [
    'username' => [
        'type' => 'text',
        'label' => 'Username',
        'validation' => ['required' => true, 'min' => 3]
    ]
];

Renderer::render([
    'schema' => $schema,
    'values' => ['username' => 'john'],
    'entity' => null,
    'form_prefix' => 'my_form'
]);
```

### Render with Entity Object

```php
Renderer::render([
    'schema' => $schema,
    'entity' => $userObject,
    'form_prefix' => 'user'
]);
```

## Available Field Types

- **text, email, url, number, date, password, tel, color, range** - Input fields
- **textarea** - Multi-line text
- **select** - Dropdown with options
- **wp_editor** - WordPress rich text editor
- **heading** - Section headings (h1-h6)

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

## Custom Templates

Override default templates by specifying template directory:

```php
Renderer::render([
    'schema' => $schema,
    'entity' => $entity,
    'form_prefix' => 'custom',
    'template_base' => '/path/to/templates'
]);
```

Template files needed:
- `input.php` - All input types
- `textarea.php` - Textarea fields
- `select.php` - Select dropdowns
- `wp-editor.php` - WordPress editor
- `label.php` - Field labels
- `help.php` - Help text
- `heading.php` - Headings

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
- `$this->get_heading_tag()` - Sanitized heading tag
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

- [Schema Validator](../schema-validator/SKILL.md) for validation
- [Schema Definition](../schema-definition/SKILL.md) for schema structure
- [Custom Templates](../custom-template-creator/SKILL.md) for template customization
- [Utility Classes](../utility-classes/SKILL.md) for Constants, Config_Sanitizer, Value_Resolver

