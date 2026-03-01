<?php
/**
 * Custom Select Template - Bootstrap Style
 *
 * This template overrides the default select template.
 * It adds Bootstrap form-select class.
 *
 * AVAILABLE METHODS:
 * - $this->get_field_id(): Get field ID attribute
 * - $this->get_field_name(): Get field name attribute
 * - $this->get_value(): Get current value
 * - $this->get_options(): Get options array
 * - $this->get_required_attr(): Get required attribute string
 * - $this->get_attributes_string(): Get custom attributes as string
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

$options_html = '';
foreach ( $this->get_options() as $option_value => $option_label ) {
	$options_html .= sprintf(
		'<option value="%s"%s>%s</option>',
		esc_attr( $option_value ),
		selected( $this->get_value(), $option_value, false ),
		esc_html( $option_label )
	);
}

printf(
	'<select id="%s" name="%s" class="form-control"%s%s>%s</select>',
	$this->get_field_id(),
	$this->get_field_name(),
	$this->get_required_attr(),
	$this->get_attributes_string(),
	$options_html
);

