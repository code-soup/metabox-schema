# Constants Usage Example

## Available Constants

```php
use CodeSoup\MetaboxSchema\Constants;

// Default values
$default_type         = Constants::DEFAULT_TYPE;           // 'text'
$default_wrapper      = Constants::DEFAULT_WRAPPER;        // 'div'
$default_rows         = Constants::DEFAULT_ROWS;           // 5
$grid_class           = Constants::DEFAULT_GRID_CLASS;     // 'grid'
$date_format          = Constants::DEFAULT_DATE_FORMAT;    // 'Y-m-d'
$required_class       = Constants::DEFAULT_REQUIRED_CLASS; // 'has-required'
$default_help_pos     = Constants::DEFAULT_HELP_POSITION;  // 'after'

// Arrays
$skip_validation      = Constants::SKIP_VALIDATION_TYPES;  // ['html']
$valid_wrappers       = Constants::VALID_WRAPPER_TAGS;     // ['', 'p', 'div', 'span', 'section', 'article']
$reserved_attrs       = Constants::RESERVED_ATTRIBUTES;    // ['id', 'name']
$valid_help_positions = Constants::VALID_HELP_POSITIONS;   // ['before', 'after']
```

## Usage Example

```php
// Check if wrapper is valid
$wrapper = 'div';
if ( in_array( $wrapper, Constants::VALID_WRAPPER_TAGS, true ) ) {
    echo 'Valid wrapper';
}

// Use default type
$type = $config['type'] ?? Constants::DEFAULT_TYPE;

// Check if validation should be skipped
if ( in_array( $type, Constants::SKIP_VALIDATION_TYPES, true ) ) {
    // Skip validation for HTML fields
}
```

## Constants Reference

| Constant | Value | Purpose |
|----------|-------|---------|
| `DEFAULT_TYPE` | `'text'` | Default field type |
| `DEFAULT_WRAPPER` | `'div'` | Default wrapper element |
| `DEFAULT_ROWS` | `5` | Default textarea rows |
| `DEFAULT_GRID_CLASS` | `'grid'` | Grid layout class |
| `DEFAULT_DATE_FORMAT` | `'Y-m-d'` | Date format |
| `DEFAULT_REQUIRED_CLASS` | `'has-required'` | Default required field class |
| `DEFAULT_HELP_POSITION` | `'after'` | Default help text position |
| `SKIP_VALIDATION_TYPES` | `['html']` | Types that skip validation |
| `VALID_WRAPPER_TAGS` | `['', 'p', 'div', ...]` | Allowed wrapper tags |
| `VALID_HELP_POSITIONS` | `['before', 'after']` | Allowed help positions |
| `RESERVED_ATTRIBUTES` | `['id', 'name']` | Auto-generated attributes |

