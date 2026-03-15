# Value_Resolver Trait Usage Example

## Using the Trait

```php
use CodeSoup\MetaboxSchema\Value_Resolver;

class Custom_Field {
    use Value_Resolver;

    public function get_value( $config, $entity ) {
        $value = $config['value'] ?? '';

        // Resolve callable
        $value = $this->resolve_callable( $value );

        // Resolve entity method
        $value = $this->resolve_entity_method( $value, $entity );

        return $value;
    }
}
```

## Callable Resolution

```php
$field  = new Custom_Field();

// Callable resolution
$config = array( 'value' => fn() => 'dynamic value' );
$value  = $field->get_value( $config, null );
// Result: 'dynamic value'
```

## Entity Method Resolution

```php
$entity = new class() {
    public function get_email() {
        return 'user@example.com';
    }
};

// Entity method resolution
$config = array( 'value' => 'get_email' );
$value  = $field->get_value( $config, $entity );
// Result: 'user@example.com'
```

## Methods

### `resolve_callable( $value ): mixed`
- Checks if value is callable (closure, function name, array callable)
- Executes callable and returns result
- Returns value as-is if not callable

### `resolve_entity_method( $value, ?object $entity ): mixed`
- Checks if value is string method name
- Checks if entity exists and has that method
- Calls entity method and returns result
- Returns value as-is if not entity method

## Used By

- `Abstract_Field` class (for value resolution)
- `Validator` class (for default value resolution)

