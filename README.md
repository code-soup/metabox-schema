# CodeSoup Metabox Schema

Drop-in schema-based form field renderer and validator. Define your fields once, render forms and validate data.

## What This Package Does

This is a focused utility package that provides two core functions:

1. **Render form fields** from a schema definition
2. **Validate submitted data** against the same schema

This is **not** a complete form solution. You handle form submission, data persistence, and integration. This package simply generates field HTML and validates data based on your schema.

## Installation

```bash
composer require codesoup/metabox-schema
```

## Basic Usage

### 1. Define Your Schema

```php
$schema = [
    'username' => [
        'type' => 'text',
        'label' => 'Username',
        'attributes' => [
            'placeholder' => 'Enter username',
            'maxlength' => 50
        ],
        'validation' => [
            'required' => true,
            'min' => 3,
            'max' => 50
        ],
        'default' => 'guest'
    ],
    'email' => [
        'type' => 'email',
        'label' => 'Email Address',
        'validation' => [
            'required' => true,
            'format' => 'email'
        ]
    ]
];
```

### 2. Render Fields

```php
use CodeSoup\MetaboxSchema\Renderer;

Renderer::render([
    'schema' => $schema,
    'entity' => null,
    'form_prefix' => 'my_form'
]);
```

This outputs HTML form fields. You wrap them in your own `<form>` tags and handle submission.

### 3. Validate Submitted Data

```php
use CodeSoup\MetaboxSchema\Validator;

$validator = new Validator();
$validated_data = $validator->validate( $_POST['my_form'], $schema );

if ( $validator->hasErrors() ) {
    $errors = $validator->getErrors();
    // Display errors to user
} else {
    // Save validated data to database
}
```

## Schema Reference

### Field Configuration

Each field in your schema supports these properties:

| Property | Type | Description |
|----------|------|-------------|
| `type` | string | Field type: text, email, url, number, date, password, tel, color, range, textarea, select, heading |
| `label` | string | Field label text |
| `default` | mixed/callable | Default value or callback function |
| `attributes` | array | HTML attributes (placeholder, maxlength, class, etc.) |
| `validation` | array | Validation rules (see below) |
| `sanitize` | callable/array | Sanitization callback(s) |
| `help` | string | Help text displayed below field |
| `wrapper` | string | Wrapper element: 'p' (default), 'div', 'span', '' (no wrapper) |
| `options` | array | Options for select fields (key => label) |
| `rows` | int | Number of rows for textarea (default: 5) |
| `grid` | string | Grid layout: 'start' or 'end' |
| `heading_tag` | string | Heading tag for heading type: h1-h6 (default: h6) |

### Validation Rules

Available validation rules in the `validation` array:

| Rule | Type | Description |
|------|------|-------------|
| `required` | bool | Field is required |
| `min` | int | Minimum length (text) or value (number) |
| `max` | int | Maximum length (text) or value (number) |
| `pattern` | string | Regular expression pattern |
| `format` | string | Format validation: 'email', 'url', 'date' |
| `validate` | callable | Custom validation callback |

### Custom Error Messages

Override default error messages in the `errors` array:

```php
'username' => [
    'validation' => [
        'required' => true,
        'min' => 3
    ],
    'errors' => [
        'required' => 'Please enter a username',
        'min' => 'Username is too short'
    ]
]
```

### Sanitization

Specify sanitization with a callback or array of callbacks:

```php
'username' => [
    'sanitize' => 'sanitize_text_field'
]

'bio' => [
    'sanitize' => ['trim', 'strip_tags', 'sanitize_textarea_field']
]
```

Default sanitization by type:
- `number` - Converts to float, supports decimals and negatives
- `email` - Email sanitization
- `url` - URL sanitization
- `textarea` - Textarea sanitization
- Others - Text field sanitization

## What You Need to Provide

This package does **not** include:

- Form tags (`<form>` wrapper)
- Submit buttons
- CSRF/nonce handling
- Form submission handling
- Data persistence
- Success/error message display
- CSS styling (except basic structure)

You integrate this into your existing form handling workflow.

## WordPress Integration

Works with WordPress functions when available, falls back to PHP alternatives otherwise. Suitable for WordPress metaboxes, settings pages, or standalone forms.

## Examples

See the `examples/` folder for complete working examples:

- `examples/simple-form.php` - Basic form rendering
- `examples/basic-usage.php` - Comprehensive schema with all field types and validation

## Requirements

- PHP 8.1 or higher

## Contributing

Issues and pull requests are welcome on [GitHub](https://github.com/code-soup/metabox-schema).

To report a bug or request a feature, please [open an issue](https://github.com/code-soup/metabox-schema/issues).

## License

MIT

