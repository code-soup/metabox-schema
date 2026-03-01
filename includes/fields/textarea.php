<?php
/**
 * Textarea Field Template
 *
 * @package CodeSoup\MetaboxSchema
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

defined( 'ABSPATH' ) || exit;

printf(
	'<textarea id="%s" name="%s" rows="%s"%s%s>%s</textarea>',
	$this->get_field_id(),
	$this->get_field_name(),
	$this->get_rows(),
	$this->get_required_attr(),
	$this->get_attributes_string(),
	$this->get_escaped_textarea_value()
);
