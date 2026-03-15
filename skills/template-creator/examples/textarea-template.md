# Custom Textarea Template Example

## Template File

Create `templates/textarea/template.php`:

```php
<?php
/**
 * Custom Textarea Template
 *
 * @var \CodeSoup\MetaboxSchema\Fields\Textarea_Field $this
 */

defined( 'ABSPATH' ) || exit;

printf(
    '<textarea id="%s" name="%s" rows="%s"%s%s class="custom-textarea">%s</textarea>',
    $this->get_field_id(),
    $this->get_field_name(),
    $this->get_rows(),
    $this->get_required_attr(),
    $this->get_attributes_string(),
    $this->get_escaped_textarea_value()
);
```

## Available Methods

Textarea-specific methods:

- `$this->get_rows()` - Number of rows (default: 5)
- `$this->get_escaped_textarea_value()` - Escaped value for textarea content

Common methods:

- `$this->get_field_id()` - Sanitized field ID
- `$this->get_field_name()` - Sanitized field name
- `$this->get_required_attr()` - ' required' or empty string
- `$this->get_attributes_string()` - All HTML attributes

## Important Notes

- Use `get_escaped_textarea_value()` for textarea content (not `get_escaped_value()`)
- All data is already sanitized/escaped
- Templates should NOT add additional escaping

