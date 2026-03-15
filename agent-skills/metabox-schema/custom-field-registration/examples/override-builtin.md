# Override Built-in Field Type Example

## Extend Existing Field Class

```php
use CodeSoup\MetaboxSchema\Fields\Input_Field;
use CodeSoup\MetaboxSchema\Field_Factory;

class Enhanced_Text_Field extends Input_Field {

    /**
     * Override to add custom attributes.
     *
     * @return array Field attributes.
     */
    public function get_attributes(): array {
        $attributes = parent::get_attributes();

        // Add custom data attributes
        $attributes['data-field-name'] = $this->config['name'];

        // Add character counter
        if ( isset( $this->config['validation']['max'] ) ) {
            $attributes['data-max-length'] = $this->config['validation']['max'];
            $attributes['data-counter']    = 'true';
        }

        return $attributes;
    }
}
```

## Override Built-in Type

```php
// Override built-in 'text' field type
Field_Factory::register_field_type( 'text', Enhanced_Text_Field::class );
```

## Use in Schema

```php
// Now all 'text' fields use Enhanced_Text_Field
$schema = array(
    'username' => array(
        'type'       => 'text',
        'label'      => 'Username',
        'validation' => array(
            'max' => 50,
        ),
    ),
);
```

## Benefits

- Extend existing field classes instead of starting from scratch
- Override specific methods to customize behavior
- Inherit all existing functionality
- No need to create new templates if using same template structure

## Common Override Points

- `get_attributes()` - Add custom HTML attributes
- `get_template_name()` - Use different template
- Any public getter method

