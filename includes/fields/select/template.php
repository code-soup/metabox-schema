<?php
/**
 * Select Field Template
 *
 * @package CodeSoup\MetaboxSchema
 * @var \CodeSoup\MetaboxSchema\Fields\Select_Field $this
 */

defined( 'ABSPATH' ) || exit;

$options_html = '';
foreach ( $this->get_options() as $option_value => $option_label ) {
	if ( is_array( $option_label ) ) {
		// Optgroup.
		$options_html .= sprintf( '<optgroup label="%s">', esc_attr( (string) $option_value ) );
		foreach ( $option_label as $opt_key => $opt_label ) {
			$options_html .= sprintf(
				'<option value="%s"%s>%s</option>',
				esc_attr( (string) $opt_key ),
				selected( $this->get_value(), $opt_key, false ),
				esc_html( $opt_label )
			);
		}
		$options_html .= '</optgroup>';
	} else {
		// Flat option.
		$options_html .= sprintf(
			'<option value="%s"%s>%s</option>',
			esc_attr( (string) $option_value ),
			selected( $this->get_value(), $option_value, false ),
			esc_html( $option_label )
		);
	}
}

printf(
	'<select id="%s" name="%s"%s%s>%s</select>',
	$this->get_escaped_field_id(),
	$this->get_escaped_field_name(),
	$this->get_required_attr(),
	$this->get_attributes_string(),
	$options_html
);
