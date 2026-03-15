# Custom Template Rendering Example

## Render with Custom Templates

```php
use CodeSoup\MetaboxSchema\Renderer;

$schema = array(
    'bio' => array(
        'type'  => 'textarea',
        'label' => 'Biography',
    ),
);

Renderer::render(
    array(
        'schema'        => $schema,
        'entity'        => $entity,
        'form_prefix'   => 'user_form',
        'template_base' => __DIR__ . '/templates',
    )
);
```

**Custom template parameter:**
- `template_base` - Path to custom template directory

**Template structure:**
```
templates/
├── input/
│   └── template.php
├── textarea/
│   └── template.php
├── select/
│   └── template.php
└── ...
```

The renderer will look for `templates/textarea/template.php` instead of the default template.

