# Basic Custom Field Example

## Create Custom Field Class

```php
use CodeSoup\MetaboxSchema\Abstract_Field;
use CodeSoup\MetaboxSchema\Field_Factory;

class Rating_Field extends Abstract_Field {

    /**
     * Get the template name for this field type.
     *
     * @return string Template name.
     */
    protected function get_template_name(): string {
        return 'rating';
    }

    /**
     * Get maximum rating value.
     *
     * @return int Maximum rating.
     */
    public function get_max_rating(): int {
        return $this->config['max_rating'] ?? 5;
    }
}
```

## Register Custom Field Type

```php
use CodeSoup\MetaboxSchema\Renderer;

$renderer = new Renderer();
$renderer->register_field_type( 'rating', Rating_Field::class );
```

**Benefits:**
- ✅ No plugin conflicts
- ✅ Isolated field registry per renderer

## Use in Schema

```php
$schema = array(
    'product_rating' => array(
        'type'       => 'rating',
        'label'      => 'Product Rating',
        'max_rating' => 5,
    ),
);

// Render with instance
$renderer->render_fields([
    'schema' => $schema,
    'form_prefix' => 'product_form'
]);

// Or use static facade (creates new instance)
Renderer::render([
    'schema' => $schema,
    'form_prefix' => 'product_form'
]);
```

## Requirements

1. Class must extend `Abstract_Field`
2. Must implement `get_template_name()` method
3. Register before rendering
4. Create template file at `templates/rating/template.php`

## Custom Methods

Add custom methods to expose field-specific configuration:
- `get_max_rating()` - Returns max rating from config
- Any other custom getters for your field type

