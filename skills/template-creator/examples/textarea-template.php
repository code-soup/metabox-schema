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

