# Custom Validation Example

## Custom Validation Callbacks

```php
use CodeSoup\MetaboxSchema\Validator;

$schema = array(
    'username' => array(
        'type'       => 'text',
        'validation' => array(
            'required' => true,
            'validate' => function ( $value ) {
                if ( username_exists( $value ) ) {
                    return 'Username already exists';
                }
                return true;
            },
        ),
    ),
    'age'      => array(
        'type'       => 'number',
        'validation' => array(
            'required' => true,
            'validate' => function ( $value ) {
                if ( $value < 18 ) {
                    return 'Must be 18 or older';
                }
                return true;
            },
        ),
    ),
);
```

## Validate

```php
$validator      = new Validator();
$validated_data = $validator->validate( $_POST, $schema );
```

## Custom Validation Rules

The `validate` key accepts a callable that:
- Receives the field value as parameter
- Returns `true` if validation passes
- Returns error message string if validation fails

**Examples:**
- Check if username exists in database
- Verify age requirements
- Complex business logic validation
- Cross-field validation

