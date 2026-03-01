---
name: utility-classes
description: Use utility classes (Constants, Config_Sanitizer, String_Formatter, Value_Resolver) from CodeSoup Metabox Schema package. Use when working with package internals, extending classes, sanitizing configuration, formatting strings, or resolving values.
license: MIT
metadata:
  author: codesoup
  version: "1.0"
  package: codesoup/metabox-schema
---

# Utility Classes

Utility classes and traits that power the CodeSoup Metabox Schema package. These can be used independently or when extending the package.

## When to Use

- Accessing package constants and defaults
- Sanitizing field configuration
- Formatting field names and strings
- Resolving callable and entity method values
- Extending package functionality
- Building custom field processors

## Constants

Centralized package constants for consistent configuration.

### Available Constants

```php
use CodeSoup\MetaboxSchema\Constants;

// Default values
Constants::DEFAULT_TYPE           // 'text'
Constants::DEFAULT_WRAPPER        // 'p'
Constants::DEFAULT_HEADING_TAG    // 'h6'
Constants::DEFAULT_ROWS           // 5
Constants::FORM_PREFIX_DELIMITER  // '_'

// Arrays
Constants::SKIP_VALIDATION_TYPES  // ['heading']
Constants::SPECIAL_TYPES          // ['textarea', 'select', 'wp_editor']
Constants::VALID_WRAPPER_TAGS     // ['', 'p', 'div', 'span', 'section', 'article']
```

### Usage Examples

```php
// Check valid wrapper
if (in_array($wrapper, Constants::VALID_WRAPPER_TAGS, true)) {
    // Valid
}

// Use default type
$type = $config['type'] ?? Constants::DEFAULT_TYPE;

// Check if validation should be skipped
if (in_array($type, Constants::SKIP_VALIDATION_TYPES, true)) {
    // Skip validation
}
```

## Config_Sanitizer

Sanitizes field configuration arrays to prevent XSS and ensure valid values.

### What It Sanitizes

- Field names, types, labels (sanitize_key, sanitize_text_field)
- Form prefix, heading tags (sanitize_key)
- Rows (absint)
- Wrapper tags (validates against whitelist)
- Options arrays (sanitizes all values)
- Attributes (sanitizes keys and values)

### Usage

```php
use CodeSoup\MetaboxSchema\Config_Sanitizer;

$sanitizer = new Config_Sanitizer();
$clean = $sanitizer->sanitize($raw_config);
```

### Extending

```php
class Custom_Sanitizer extends Config_Sanitizer {
    public function sanitize(array $config): array {
        $config = parent::sanitize($config);
        
        // Add custom sanitization
        if (isset($config['custom_field'])) {
            $config['custom_field'] = sanitize_text_field($config['custom_field']);
        }
        
        return $config;
    }
}
```

## String_Formatter

String formatting utilities for field names and conversions.

### Methods

```php
use CodeSoup\MetaboxSchema\String_Formatter;

// Format field name for display
String_Formatter::format_field_name('user_email')  // "User email"
String_Formatter::format_field_name('first-name')  // "First name"

// Convert to ID format (dashes)
String_Formatter::to_id_format('user_email')       // "user-email"

// Convert to attribute format (underscores)
String_Formatter::to_attribute_format('user-email') // "user_email"
```

### Usage in Validator

The Validator class uses String_Formatter automatically for field labels when no label is provided:

```php
'user_email' => [
    'validation' => ['required' => true]
    // Error message: "User email is required"
]
```

## Value_Resolver Trait

Provides value resolution logic for callable and entity method values.

### Methods

```php
// Resolve callable values
protected function resolve_callable($value): mixed

// Resolve entity method calls
protected function resolve_entity_method($value, ?object $entity): mixed
```

### Usage in Custom Classes

```php
use CodeSoup\MetaboxSchema\Value_Resolver;

class My_Processor {
    use Value_Resolver;
    
    public function get_value($value, $entity = null) {
        $value = $this->resolve_callable($value);
        $value = $this->resolve_entity_method($value, $entity);
        return $value;
    }
}
```

### How It Works

**resolve_callable:**
- Checks if value is callable (closure, function name, array callable)
- Executes callable and returns result
- Returns value as-is if not callable

**resolve_entity_method:**
- Checks if value is string method name
- Checks if entity exists and has that method
- Calls entity method and returns result
- Returns value as-is if not entity method

### Used By

- Field class (for value resolution)
- Validator class (for default value resolution)

## Important Notes

- All utility classes are in `CodeSoup\MetaboxSchema` namespace
- Config_Sanitizer uses Constants for validation
- String_Formatter methods are static
- Value_Resolver is a trait, not a class
- All sanitization happens at construction/validation time
- All escaping happens at output/render time

## See Also

- [Schema Field Renderer](../schema-field-renderer/SKILL.md) - Uses Value_Resolver and Config_Sanitizer
- [Schema Validator](../schema-validator/SKILL.md) - Uses Value_Resolver and String_Formatter
- [Schema Definition](../schema-definition/SKILL.md) - Field configuration structure

