# Basic Validation Example

## Define Schema with Validation Rules

```php
use CodeSoup\MetaboxSchema\Validator;

$schema = array(
    'username' => array(
        'type'       => 'text',
        'validation' => array(
            'required' => true,
            'min'      => 3,
            'max'      => 50,
        ),
    ),
    'email'    => array(
        'type'       => 'email',
        'validation' => array(
            'required' => true,
        ),
    ),
);
```

## Validate POST Data

```php
$validator      = new Validator();
// Field names are nested under your form prefix in POST.
$form_prefix    = 'my_form';
$validated_data = $validator->validate( $_POST[ $form_prefix ] ?? array(), $schema );

if ( $validator->has_errors() ) {
    foreach ( $validator->get_errors() as $field => $error ) {
        echo $error;
    }
} else {
    // Use $validated_data (already sanitized)
    // Save to database, etc.
}
```

## Methods

- `validate( $data, $schema )` - Validates and sanitizes data, returns clean array
- `has_errors()` - Returns true if validation failed
- `get_errors()` - Returns array of field => error_message

## Automatic Sanitization

Data is automatically sanitized based on field type:
- `email` → `sanitize_email()`
- `url` → `esc_url_raw()`
- `textarea` → `sanitize_textarea_field()`
- `number` → int/float when numeric
- `text` / other scalars → `sanitize_text_field()`
- `checkbox_group` → array of sanitized strings; options validated against schema

