<?php
/**
 * Select Field Template
 *
 * @package CodeSoup\MetaboxSchema
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

defined( 'ABSPATH' ) || exit;

$options_html = '';
foreach ( $this->get_options() as $option_value => $option_label ) {
	$options_html .= sprintf(
		'<option value="%s"%s>%s</option>',
		$option_value,
		selected( $this->get_value(), $option_value, false ),
		$option_label
	);
}

printf(
	'<select id="%s" name="%s"%s%s>%s</select>',
	$this->get_field_id(),
	$this->get_field_name(),
	$this->get_required_attr(),
	$this->get_attributes_string(),
	$options_html
);
