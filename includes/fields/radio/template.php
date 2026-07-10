<?php
/**
 * Radio Field Template
 *
 * @package CodeSoup\MetaboxSchema
 * @var \CodeSoup\MetaboxSchema\Fields\Radio_Field $this
 */

defined( 'ABSPATH' ) || exit;

$options = $this->get_options();

if ( empty( $options ) ) {
	return;
}

$output   = array();
$output[] = sprintf(
	'<div id="%s" class="radio-group">',
	$this->get_field_id()
);

foreach ( $options as $option_value => $option_label ) {
	$value    = esc_attr( (string) $option_value );
	$field_id = sprintf(
		'%s-%s',
		$this->get_field_id(),
		$value
	);

	$output[] = sprintf(
		'<label for="%s">',
		$field_id
	);

	$output[] = sprintf(
		'<input id="%s" type="radio" name="%s" value="%s"%s> %s</label>',
		$field_id,
		esc_attr( $this->get_field_name() ),
		$value,
		checked( $this->is_selected( $value ), true, false ),
		esc_html( $option_label )
	);
}

$output[] = '</div>';

echo implode( '', $output );
