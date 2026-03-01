---
name: schema-definition
description: Define and structure field schemas for CodeSoup Metabox Schema package. Use when creating schemas, defining fields, configuring validation, or when user mentions schema structure, field configuration, or form definitions.
license: MIT
metadata:
  author: codesoup
  version: "1.0"
  package: codesoup/metabox-schema
---

# Schema Definition

Define and structure field schemas correctly for the CodeSoup Metabox Schema package.

## When to Use

- Creating new field schemas
- Defining form structures
- Configuring field validation
- Setting up field properties
- Working with entity value resolution

## Basic Schema Structure

```php
$schema = [
    'field_name' => [
        'type' => 'text',
        'label' => 'Field Label',
        'value' => 'static_value',
        'default' => 'default_value',
        'attributes' => [],
        'validation' => [],
        'sanitize' => null,
        'help' => '',
        'wrapper' => 'p'
    ]
];
```

## Field Properties Reference

### Required Properties

- **type** (string) - Field type: text, email, url, number, date, password, tel, color, range, textarea, select, wp_editor, heading

### Common Properties

- **label** (string) - Field label text
- **value** (mixed/callable/string) - Current value, callable, or entity method name
- **default** (mixed/callable) - Default value or callback (used when value is empty)
- **attributes** (array) - HTML attributes (class, placeholder, maxlength, data-*, etc.)
  - Note: `id` and `name` are auto-generated and ignored if provided in attributes
  - Auto-generated ID format: `form-prefix-field-name`
- **validation** (array) - Validation rules
- **errors** (array) - Custom error messages for validation rules
- **sanitize** (callable/array) - Sanitization callback(s)
- **help** (string) - Help text displayed below field
- **wrapper** (string) - Wrapper element: 'p' (default), 'div', 'span', '' (no wrapper)

### Field-Specific Properties

- **options** (array) - For select fields (key => label)
- **rows** (int) - For textarea/wp_editor (default: 5)
- **grid** (string) - Grid layout: 'start' or 'end'
- **heading_tag** (string) - For heading type: h1-h6 (default: h6)
- **editor_settings** (array) - For wp_editor (WordPress editor config)

## Value Resolution

### Static Values

```php
'username' => [
    'type' => 'text',
    'value' => 'john_doe'
]
```

### Entity Method

```php
'email' => [
    'type' => 'email',
    'value' => 'getEmail'  // Calls $entity->getEmail()
]
```

### Callable

```php
'created_at' => [
    'type' => 'date',
    'value' => function() {
        return date('Y-m-d');
    }
]
```

### Values Array

When rendering with values array, field name is used as key:

```php
Renderer::render([
    'schema' => $schema,
    'values' => ['username' => 'john'],  // Overrides schema value
    'entity' => null,
    'form_prefix' => 'form'
]);
```

## Default Values

### Static Default

```php
'country' => [
    'type' => 'text',
    'default' => 'USA'
]
```

### Callable Default

```php
'registration_date' => [
    'type' => 'date',
    'default' => function() {
        return date('Y-m-d');
    }
]
```

## Field Types Examples

### Text Input

```php
'username' => [
    'type' => 'text',
    'label' => 'Username',
    'attributes' => [
        'class' => 'regular-text form-control',
        'placeholder' => 'Enter username',
        'maxlength' => 50,
        'data-validate' => 'username'
    ],
    'validation' => [
        'required' => true,
        'min' => 3,
        'max' => 50
    ],
    'errors' => [
        'required' => 'Username is required',
        'min' => 'Username must be at least 3 characters'
    ]
]
// Renders with auto-generated id="form-prefix-username"
```

### Email

```php
'email' => [
    'type' => 'email',
    'label' => 'Email Address',
    'validation' => [
        'required' => true,
        'format' => 'email'
    ]
]
```

### Select Dropdown

```php
'role' => [
    'type' => 'select',
    'label' => 'User Role',
    'options' => [
        'admin' => 'Administrator',
        'editor' => 'Editor',
        'author' => 'Author'
    ],
    'validation' => ['required' => true]
]
```

### Textarea

```php
'bio' => [
    'type' => 'textarea',
    'label' => 'Biography',
    'rows' => 10,
    'attributes' => ['class' => 'large-text']
]
```

### WordPress Editor

```php
'content' => [
    'type' => 'wp_editor',
    'label' => 'Page Content',
    'rows' => 15,
    'editor_settings' => [
        'media_buttons' => true,
        'teeny' => false,
        'tinymce' => [
            'toolbar1' => 'formatselect,bold,italic,link'
        ]
    ]
]
```

## See Also

- [Schema Field Renderer](../schema-field-renderer/SKILL.md) for rendering
- [Schema Validator](../schema-validator/SKILL.md) for validation
- [Custom Templates](../custom-template-creator/SKILL.md) for customization
- [Utility Classes](../utility-classes/SKILL.md) for Constants and default values

