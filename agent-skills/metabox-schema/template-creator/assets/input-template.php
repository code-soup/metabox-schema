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

