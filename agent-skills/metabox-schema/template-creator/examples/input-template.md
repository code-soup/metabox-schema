# Custom Input Template Example

## Template File

Create `templates/input/template.php`:

```php
<?php
/**
 * Custom Input Template
 *
 * @var \CodeSoup\MetaboxSchema\Fields\Input_Field $this
 */

defined( 'ABSPATH' ) || exit;

printf(
    '<div class="form-field"><input id="%s" name="%s" type="%s" value="%s"%s%s class="custom-input" /></div>',
    $this->get_field_id(),
    $this->get_field_name(),
    $this->get_type(),
    $this->get_escaped_value(),
    $this->get_required_attr(),
    $this->get_attributes_string()
);
```

## Available Methods

Inside templates, `$this` refers to the Field object:

- `$this->get_field_id()` - Sanitized field ID
- `$this->get_field_name()` - Sanitized field name
- `$this->get_type()` - Field type (text, email, etc.)
- `$this->get_escaped_value()` - Escaped value for inputs
- `$this->get_required_attr()` - ' required' or empty string
- `$this->get_attributes_string()` - All HTML attributes

## Important Notes

- All data from Field methods is already sanitized/escaped
- Templates should NOT add additional escaping
- Use `get_escaped_value()` for input fields
- Templates are included via PHP `include`, so `$this` context is available

