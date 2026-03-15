# Basic Rendering Example

## Define Schema

```php
use CodeSoup\MetaboxSchema\Renderer;

$schema = array(
    'username' => array(
        'type'  => 'text',
        'label' => 'Username',
    ),
    'email'    => array(
        'type'  => 'email',
        'label' => 'Email',
    ),
);
```

## Render Form

```php
Renderer::render(
    array(
        'schema'      => $schema,
        'entity'      => $entity,
        'form_prefix' => 'user_form',
    )
);
```

**Parameters:**
- `schema` - Field definitions array
- `entity` - Object with getter methods for field values
- `form_prefix` - Prefix for field names and IDs

**Output:**
Renders HTML form fields with auto-generated IDs like `user_form-username`, `user_form-email`.

