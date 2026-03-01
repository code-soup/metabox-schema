---
name: schema-validator
description: Validate form data against schema definitions using CodeSoup Metabox Schema package. Use when validating user input, form submissions, data integrity, or when user mentions validation, sanitization, error messages, or data verification.
license: MIT
metadata:
  author: codesoup
  version: "1.0"
  package: codesoup/metabox-schema
---

# Schema Validator

Validate and sanitize form data against schema definitions using the CodeSoup Metabox Schema Validator class.

## When to Use

- Validating form submissions
- Sanitizing user input
- Checking data integrity
- Generating validation error messages
- Processing WordPress form data

## Basic Usage

```php
use CodeSoup\MetaboxSchema\Validator;

$schema = [
    'email' => [
        'type' => 'email',
        'label' => 'Email Address',
        'validation' => [
            'required' => true,
            'format' => 'email'
        ]
    ],
    'age' => [
        'type' => 'number',
        'label' => 'Age',
        'validation' => [
            'required' => true,
            'min' => 18,
            'max' => 120
        ]
    ]
];

$validator = new Validator();
$validated_data = $validator->validate( $_POST['my_form'], $schema );

if ( $validator->has_errors() ) {
    foreach ( $validator->get_errors() as $field => $error ) {
        echo $error;
    }
} else {
    // Use $validated_data (already sanitized)
    // Save to database, etc.
}
```

## Public Methods

- `validate( array $data, array $schema ): array` - Validate and sanitize data
- `get_errors(): array` - Get validation errors (field_name => error_message)
- `has_errors(): bool` - Check if validation has errors

## Validation Rules

| Rule | Type | Description |
|------|------|-------------|
| `required` | bool | Field is required |
| `min` | int | Minimum length (text) or value (number) |
| `max` | int | Maximum length (text) or value (number) |
| `pattern` | string | Regular expression pattern |
| `format` | string | Format validation: 'email', 'url', 'date' |
| `date_format` | string | Date format for date validation (default: 'Y-m-d') |
| `validate` | callable | Custom validation callback |

## Format Validation

### Email Validation

Uses WordPress `is_email()` function:

```php
'email' => [
    'validation' => ['format' => 'email']
]
```

### URL Validation

Uses WordPress `wp_http_validate_url()` function:

```php
'website' => [
    'validation' => ['format' => 'url']
]
```

### Date Validation

Uses strict `DateTime::createFromFormat()` validation:

```php
'birth_date' => [
    'type' => 'date',
    'validation' => [
        'format' => 'date',
        'date_format' => 'd/m/Y'  // Optional, default: Y-m-d
    ]
]
```

## Custom Error Messages

Override default error messages:

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

## Custom Validation

Use callable for custom validation logic:

```php
'password' => [
    'validation' => [
        'validate' => function($value) {
            if (strlen($value) < 8) {
                return 'Password must be at least 8 characters';
            }
            if (!preg_match('/[A-Z]/', $value)) {
                return 'Password must contain uppercase letter';
            }
            return true;
        }
    ]
]
```

## Sanitization

Automatic sanitization based on field type:

- **email** - `sanitize_email()`
- **url** - `esc_url_raw()`
- **textarea** - `sanitize_textarea_field()`
- **text/other** - `sanitize_text_field()`

Custom sanitization:

```php
'username' => [
    'sanitize' => 'strtolower'  // Single callback
]

'tags' => [
    'sanitize' => ['trim', 'strtolower']  // Multiple callbacks
]
```

## Important Notes

- Validation happens before sanitization
- All WordPress sanitization functions are used
- Date validation is strict (exact format match required)
- Custom validators should return `true` or error message string
- Requires WordPress to be loaded

## Internal Architecture

The Validator class uses several utility components:

**Value_Resolver Trait:**
- Resolves callable default values
- Ensures consistent behavior with Field class
- Example: `default => fn() => date('Y-m-d')`

**String_Formatter:**
- Formats field names for error messages
- Converts `user_name` to "User name"
- Converts `first-name` to "First name"
- Used automatically when no label provided

**Constants:**
- Provides DEFAULT_TYPE for fields without type
- Defines SKIP_VALIDATION_TYPES (e.g., 'heading')
- Ensures consistency across package

**Field Name Formatting:**
```php
// Automatic formatting when no label
'user_email' => [
    'validation' => ['required' => true]
    // Error: "User email is required"
]

// With custom label
'user_email' => [
    'label' => 'Email Address',
    'validation' => ['required' => true]
    // Error: "Email Address is required"
]
```

**When Extending Validator:**
```php
use CodeSoup\MetaboxSchema\Validator;
use CodeSoup\MetaboxSchema\String_Formatter;
use CodeSoup\MetaboxSchema\Constants;

class Custom_Validator extends Validator {
    protected function build_field_context(string $field_name, array $field_config): array {
        $context = parent::build_field_context($field_name, $field_config);

        // String_Formatter is used internally for labels
        // Constants provides DEFAULT_TYPE

        return $context;
    }
}
```

## See Also

- [Schema Field Renderer](../schema-field-renderer/SKILL.md) for rendering
- [Schema Definition](../schema-definition/SKILL.md) for schema structure
- [Utility Classes](../utility-classes/SKILL.md) for String_Formatter, Value_Resolver, Constants

