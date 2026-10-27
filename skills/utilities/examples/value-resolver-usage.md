# Value_Resolver Trait Usage Example

## Resolution order (matches `Abstract_Field`)

1. `resolve_entity_method( $value, $entity )`
2. `resolve_callable( $value )`

Entity methods take precedence over global functions with the same name.

## Using the Trait

```php
use CodeSoup\MetaboxSchema\Value_Resolver;

class Custom_Processor {
    use Value_Resolver;

    public function resolve( $config, ?object $entity ) {
        $value = $config['value'] ?? $config['default'] ?? '';
        $value = $this->resolve_entity_method( $value, $entity );
        $value = $this->resolve_callable( $value );
        return $value;
    }
}
```

## Callable Resolution

```php
$processor = new Custom_Processor();
$value     = $processor->resolve(
    array( 'value' => fn() => 'dynamic value' ),
    null
);
// 'dynamic value'
```

## Entity Method Resolution

```php
$entity = new class() {
    public function get_email() {
        return 'user@example.com';
    }
};

$value = $processor->resolve(
    array( 'value' => 'get_email' ),
    $entity
);
// 'user@example.com'
```

## Methods

### `resolve_entity_method( $value, ?object $entity ): mixed`

- String method name on `$entity` when `method_exists`
- Otherwise returns `$value` unchanged

### `resolve_callable( $value ): mixed`

- Executes callables (no arguments)
- Otherwise returns `$value` unchanged

## Used By

- `Abstract_Field` — field display values
- `Validator` — `default` resolution on validation failures and empty optional fields
