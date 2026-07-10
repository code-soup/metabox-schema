<?php
/**
 * Checkbox Field Template
 *
 * @package CodeSoup\MetaboxSchema
 * @var \CodeSoup\MetaboxSchema\Fields\Checkbox_Field $this
 */

defined( 'ABSPATH' ) || exit;

printf(
	'<input id="%s" name="%s" type="checkbox" value="1"%s%s />',
	$this->get_escaped_field_id(),
	$this->get_escaped_field_name(),
	checked( ! empty( $this->get_value() ), true, false ),
	$this->get_attributes_string()
);
