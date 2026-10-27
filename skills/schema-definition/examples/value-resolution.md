# Value Resolution Example

Demonstrates how field values are resolved when **rendering** (see `Abstract_Field::resolve_value()`).

## Static Value

```php
'status' => array(
    'type'  => 'text',
    'value' => 'active',
),
```

## Entity Method Resolution

```php
'email' => array(
    'type'  => 'email',
    'value' => 'get_email', // Calls $entity->get_email() when entity is set
),
```

Entity methods run **before** global/callable resolution, so a method name like `get_post_types` on `$entity` is not treated as the WordPress function.

## Callable (Closure)

```php
'created_at' => array(
    'type'  => 'date',
    'value' => function () {
        return gmdate( 'Y-m-d' );
    },
),
```

## Default (render + validation)

### Static default

```php
'country' => array(
    'type'    => 'text',
    'default' => 'USA',
),
```

Used when building the field if no `value` key is present. On **validation**, when POST is empty for an optional field, the validator returns the resolved `default`.

### Callable default

```php
'registration_date' => array(
    'type'    => 'date',
    'default' => function () {
        return gmdate( 'Y-m-d' );
    },
),
```

## Render resolution order

1. If `value` key exists in field config → use it (including `null`).
2. Else if `default` key exists → use it.
3. Else use `''`.
4. **`resolve_entity_method( $value, $entity )`** — string method names on `$entity`.
5. **`resolve_callable( $value )`** — closures, invokables, or global functions if still callable.

**Note:** A string that matches a global PHP function name can be invoked at step 5 if it is not an entity method. Prefer closures or entity methods for dynamic values.

## Validator defaults

On empty POST input, optional fields resolve `default` from the schema (including callables via `Value_Resolver`). Required empty fields add an error and still fall back to `default` on the validated output when validation fails for other rules.
