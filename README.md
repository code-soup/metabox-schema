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
        'value' => 'john_doe',
        'default' => 'guest'
    ],
    'email' => [
        'type' => 'email',
        'label' => 'Email Address',
        'validation' => [
            'required' => true,
            'format' => 'email'
        ],
        'value' => 'john@example.com'
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
| `value` | mixed/callable/string | Current field value, callable, or entity method name |
| `default` | mixed/callable | Default value or callback function (used when value is empty) |
| `attributes` | array | HTML attributes (placeholder, maxlength, class, etc.) |
| `validation` | array | Validation rules (see below) |
| `sanitize` | callable/array | Sanitization callback(s) |
| `help` | string | Help text displayed below field |
| `wrapper` | string | Wrapper element: 'p' (default), 'div', 'span', '' (no wrapper) |
| `options` | array | Options for select fields (key => label) |
| `rows` | int | Number of rows for textarea (default: 5) |
| `grid` | string | Grid layout: 'start' or 'end' |
| `heading_tag` | string | Heading tag for heading type: h1-h6 (default: h6) |

### Field Values

The `value` property determines what value is displayed in the field. It supports four approaches:

**1. Static Value**

```php
'username' => [
    'type' => 'text',
    'label' => 'Username',
    'value' => 'john_doe'
]
```

**2. Entity Method Name (with entity)**

When an entity object is provided, `value` can be a method name that will be called on the entity:

```php
'username' => [
    'type' => 'text',
    'label' => 'Username',
    'value' => 'getUsername'
]

// Renderer::render([
//     'schema' => $schema,
//     'entity' => $userObject,
//     'form_prefix' => 'my_form'
// ]);
```

**3. Callable (deferred execution)**

Pass a function name or callable - it will be executed when the field is rendered:

```php
'username' => [
    'type' => 'text',
    'label' => 'Username',
    'value' => 'get_current_user_name'
]
```

**4. Immediate Execution**

Execute the function when defining the schema:

```php
'username' => [
    'type' => 'text',
    'label' => 'Username',
    'value' => get_current_user_name()
]
```

**Priority**: `value` takes precedence over `default`. If `value` is not set, `default` is used.

### How Value Resolution Works

The Field class resolves values in this order:

1. **Check if value is callable** - If `is_callable($value)` returns true, the callable is executed
2. **Check if value is entity method** - If value is a string, entity exists, and entity has that method, call it
3. **Return static value** - Otherwise, return the value as-is

**Important Distinctions:**

```php
// Callable reference (deferred) - Field class calls it during render
'value' => 'get_current_user_name'

// Immediate execution - Executes NOW when schema is defined
'value' => get_current_user_name()

// Entity method (deferred) - Field class calls it during render
'value' => 'getUsername'  // requires entity object

// Static value - Used as-is
'value' => 'john_doe'
```

**When to use each approach:**

- **Callable reference**: When you want the value fetched at render time (e.g., current timestamp, session data)
- **Immediate execution**: When you want the value captured at schema definition time
- **Entity method**: When working with objects (WordPress posts, database models, etc.)
- **Static value**: When you have a fixed value or pre-fetched data

**Complete Example**

```php
// Without entity - use static values
$schema = [
    'username' => [
        'type' => 'text',
        'label' => 'Username',
        'value' => 'john_doe'
    ]
];

Renderer::render([
    'schema' => $schema,
    'entity' => null,
    'form_prefix' => 'my_form'
]);

// With entity - use method names
class User {
    public function getUsername(): string {
        return 'john_doe';
    }
}

$schema = [
    'username' => [
        'type' => 'text',
        'label' => 'Username',
        'value' => 'getUsername'
    ]
];

Renderer::render([
    'schema' => $schema,
    'entity' => new User(),
    'form_prefix' => 'my_form'
]);

// Callable examples
$schema = [
    'timestamp' => [
        'type' => 'text',
        'label' => 'Current Time',
        'value' => 'time'  // Deferred: calls time() when field renders
    ],
    'captured_time' => [
        'type' => 'text',
        'label' => 'Captured Time',
        'value' => time()  // Immediate: captures time() NOW
    ]
];
```

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

## Extending Classes

All core classes (`Validator`, `Field`, `Renderer`) are designed to be extensible. All internal methods are `protected`, allowing you to extend and customize behavior.

### Extend Validator

Add custom validation rules and sanitization:

```php
use CodeSoup\MetaboxSchema\Validator;

class CustomValidator extends Validator {
    protected function sanitizeByType($value, string $type): mixed {
        return match($type) {
            'phone' => $this->sanitizePhone($value),
            'slug' => $this->sanitizeSlug($value),
            default => parent::sanitizeByType($value, $type),
        };
    }

    protected function validateValue($value, array $context): string|bool {
        // Add custom validation logic
        if ($context['type'] === 'phone') {
            return $this->validatePhone($value, $context);
        }
        return parent::validateValue($value, $context);
    }

    private function sanitizePhone($value): string {
        return preg_replace('/[^0-9+\-() ]/', '', (string) $value);
    }
}

$validator = new CustomValidator();
```

### Extend Renderer

Customize rendering behavior:

```php
use CodeSoup\MetaboxSchema\Renderer;

class BootstrapRenderer extends Renderer {
    protected function openGrid(): void {
        printf('<div class="row">');
    }

    protected function renderField(...$args): void {
        printf('<div class="col-md-6">');
        parent::renderField(...$args);
        printf('</div>');
    }
}

BootstrapRenderer::render(['schema' => $schema, ...]);
```

### Extend Field

Customize field rendering:

```php
use CodeSoup\MetaboxSchema\Field;

class CustomField extends Field {
    protected function generateFieldId(): string {
        return 'custom-' . parent::generateFieldId();
    }

    public function getAttributesString(): string {
        // Add custom data attributes
        $attrs = $this->getAttributes();
        $attrs['data-field-name'] = $this->config['name'];
        // ... custom logic
    }
}
```

## Custom Templates

You can override field templates to customize HTML output.

### Override All Templates

Use `template_base` to specify a custom template directory:

```php
Renderer::render([
    'schema' => $schema,
    'entity' => null,
    'form_prefix' => 'my_form',
    'template_base' => __DIR__ . '/templates'
]);
```

Create these files in your templates directory:
- `input.php` - For all input types
- `textarea.php` - For textarea fields
- `select.php` - For select dropdowns
- `label.php` - For field labels
- `help.php` - For help text
- `heading.php` - For heading elements

### Override Single Field Template

Use `template_path` in a specific field to override just that field:

```php
'featured_content' => [
    'type' => 'textarea',
    'label' => 'Featured Content',
    'template_path' => __DIR__ . '/templates/featured-textarea.php'
]
```

### Available Methods in Templates

Inside template files, `$this` refers to the Field object:

```php
$this->getFieldId()          // Field ID attribute
$this->getFieldName()        // Field name attribute
$this->getLabel()            // Field label
$this->getValue()            // Field value
$this->getType()             // Field type
$this->isRequired()          // Is field required?
$this->getRequiredAttr()     // Required attribute string
$this->getAttributesString() // Custom attributes string
$this->getHelp()             // Help text
$this->getRows()             // Textarea rows
$this->getOptions()          // Select options
$this->getHeadingTag()       // Heading tag (h1-h6)
```

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

## WordPress Integration Example

Here is how to use this package with WordPress metaboxes using a class-based approach:

**1. Define your schema** (`wp/schema.php`):

```php
return [
    'product_price' => [
        'type' => 'number',
        'label' => 'Product Price',
        'validation' => ['required' => true, 'min' => 0],
        'value' => 'getProductPrice'
    ],
    'product_sku' => [
        'type' => 'text',
        'label' => 'SKU',
        'validation' => ['required' => true],
        'value' => 'getProductSku'
    ]
];
```

**2. Create metabox class**:

```php
use CodeSoup\MetaboxSchema\Renderer;
use CodeSoup\MetaboxSchema\Validator;

class ProductDetailsMetabox {

    private array $schema;

    public function __construct() {
        $this->schema = require __DIR__ . '/wp/schema.php';
        $this->registerHooks();
    }

    private function registerHooks(): void {
        add_action( 'add_meta_boxes', [ $this, 'registerMetabox' ] );
        add_action( 'save_post_product', [ $this, 'saveMetabox' ] );
        add_action( 'admin_notices', [ $this, 'displayErrors' ] );
    }

    public function registerMetabox(): void {
        add_meta_box(
            'product_details',
            'Product Details',
            [ $this, 'renderMetabox' ],
            'product',
            'normal',
            'high'
        );
    }

    public function renderMetabox( $post ): void {
        wp_nonce_field( 'product_details_nonce', 'product_details_nonce' );

        // Create entity wrapper for post meta
        $entity = new class($post) {
            public function __construct(private $post) {}

            public function getProductPrice() {
                return get_post_meta($this->post->ID, 'product_price', true);
            }

            public function getProductSku() {
                return get_post_meta($this->post->ID, 'product_sku', true);
            }
        };

        Renderer::render([
            'schema' => $this->schema,
            'entity' => $entity,
            'form_prefix' => 'product_meta'
        ]);
    }

    public function saveMetabox( int $post_id ): void {
        // Verify nonce and permissions
        if ( ! $this->shouldSave( $post_id ) ) {
            return;
        }

        // Validate data
        $validator = new Validator();
        $validated_data = $validator->validate( $_POST['product_meta'], $this->schema );

        if ( $validator->hasErrors() ) {
            set_transient( 'product_meta_errors_' . $post_id, $validator->getErrors(), 45 );
            return;
        }

        // Save validated data
        foreach ( $validated_data as $key => $value ) {
            update_post_meta( $post_id, $key, $value );
        }
    }

    public function displayErrors(): void {
        global $post;

        $errors = get_transient( 'product_meta_errors_' . $post->ID );

        if ( $errors ) {
            // Display error notice
        }
    }
}

new ProductDetailsMetabox();
```

See `examples/wordpress-metabox.php` and `examples/wp/schema.php` for the complete implementation.

## Examples

See the `examples/` folder for complete working examples:

- `examples/simple-form.php` - Basic form rendering
- `examples/basic-usage.php` - Comprehensive schema with all field types and validation
- `examples/wordpress-metabox.php` - Complete WordPress metabox class implementation
- `examples/wp/schema.php` - Example schema for WordPress metabox
- `examples/custom-templates.php` - Using custom template directory for all fields
- `examples/override-single-field.php` - Override template for a specific field
- `examples/templates/` - Custom template files (Bootstrap-style examples)
- `examples/extend-validator.php` - Extending Validator class with custom validation rules
- `examples/extend-renderer.php` - Extending Renderer and Field classes with Bootstrap integration

## Requirements

- PHP 8.1 or higher

## Contributing

Issues and pull requests are welcome on [GitHub](https://github.com/code-soup/metabox-schema).

To report a bug or request a feature, please [open an issue](https://github.com/code-soup/metabox-schema/issues).

## License

MIT

