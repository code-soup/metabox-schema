<?php
/**
 * Input Field Template
 *
 * @package CodeSoup\MetaboxSchema
 * @var \CodeSoup\MetaboxSchema\Fields\Input_Field $this
 */

defined( 'ABSPATH' ) || exit;

printf(
	'<input id="%s" name="%s" type="%s" value="%s"%s%s />',
	$this->get_escaped_field_id(),
	$this->get_escaped_field_name(),
	$this->get_type(),
	$this->get_escaped_value(),
	$this->get_required_attr(),
	$this->get_attributes_string()
);
