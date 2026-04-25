# Color Picker Field Example

## Complete Custom Field with Multiple Options

```php
use CodeSoup\MetaboxSchema\Abstract_Field;
use CodeSoup\MetaboxSchema\Field_Factory;

class Color_Picker_Field extends Abstract_Field {

    protected function get_template_name(): string {
        return 'color-picker';
    }

    public function get_color_format(): string {
        return $this->config['color_format'] ?? 'hex';
    }

    public function get_palette(): array {
        return $this->config['palette'] ?? array();
    }

    public function has_alpha(): bool {
        return $this->config['alpha'] ?? false;
    }
}
```

## Register

```php
use CodeSoup\MetaboxSchema\Renderer;

$renderer = new Renderer();
$renderer->register_field_type( 'color_picker', Color_Picker_Field::class );
```

## Use in Schema

```php
$schema = array(
    'brand_color'  => array(
        'type'         => 'color_picker',
        'label'        => 'Brand Color',
        'color_format' => 'hex',
        'palette'      => array( '#FF0000', '#00FF00', '#0000FF' ),
        'alpha'        => true,
        'validation'   => array(
            'required' => true,
        ),
    ),
    'accent_color' => array(
        'type'         => 'color_picker',
        'label'        => 'Accent Color',
        'color_format' => 'rgb',
    ),
);

$renderer->render_fields([
    'schema' => $schema,
    'form_prefix' => 'branding'
]);
```

## Custom Configuration

The field exposes custom configuration through methods:
- `get_color_format()` - Returns 'hex', 'rgb', or 'hsl'
- `get_palette()` - Returns array of predefined colors
- `has_alpha()` - Returns boolean for alpha channel support

These methods can be used in the template to customize rendering.

