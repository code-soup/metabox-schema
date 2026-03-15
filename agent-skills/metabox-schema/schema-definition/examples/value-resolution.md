# Value Resolution Example

Demonstrates different ways to resolve field values.

## Static Value

```php
'status' => array(
    'type'  => 'text',
    'value' => 'active',
),
```

The field will always have the value `'active'`.

## Entity Method Resolution

```php
'email' => array(
    'type'  => 'email',
    'value' => 'get_email', // Calls $entity->get_email()
),
```

When rendering, the system calls `$entity->get_email()` to get the value.

**Entity example:**
```php
$entity = new class() {
    public function get_email() {
        return 'user@example.com';
    }
};
```

## Callable (Closure)

```php
'created_at' => array(
    'type'  => 'date',
    'value' => function () {
        return date( 'Y-m-d' );
    },
),
```

The closure is executed when the field is rendered, returning the current date.

## Default Values

### Static Default

```php
'country' => array(
    'type'    => 'text',
    'default' => 'USA',
),
```

Used when no value is provided.

### Callable Default

```php
'registration_date' => array(
    'type'    => 'date',
    'default' => function () {
        return date( 'Y-m-d' );
    },
),
```

The default value is computed dynamically when needed.

## Resolution Priority

1. **`value`** from schema config
2. **Entity method** (if value is a string method name)
3. **Callable** (if value is a closure/function)
4. **`default`** (if no value found)
5. **Empty string** (if no default)

