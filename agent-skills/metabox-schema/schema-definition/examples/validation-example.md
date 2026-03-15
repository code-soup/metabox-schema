# Validation Example

Demonstrates validation rules in schema.

## Text Field with Pattern Validation

```php
'username' => array(
    'type'       => 'text',
    'label'      => 'Username',
    'validation' => array(
        'required' => true,
        'min'      => 3,
        'max'      => 50,
        'pattern'  => '/^[a-zA-Z0-9_]+$/',
    ),
),
```

**Validation rules:**
- `required` - Field must have a value
- `min` - Minimum length (3 characters)
- `max` - Maximum length (50 characters)
- `pattern` - Regex pattern (alphanumeric and underscore only)

## Number Field with Range Validation

```php
'age' => array(
    'type'       => 'number',
    'label'      => 'Age',
    'validation' => array(
        'required' => true,
        'min'      => 18,
        'max'      => 120,
    ),
),
```

**Validation rules:**
- `min` - Minimum value (18)
- `max` - Maximum value (120)

## Date Field with Format Validation

```php
'birthdate' => array(
    'type'       => 'date',
    'label'      => 'Birth Date',
    'validation' => array(
        'required'    => true,
        'date_format' => 'Y-m-d',
    ),
),
```

**Validation rules:**
- `date_format` - Expected date format (Y-m-d = YYYY-MM-DD)

