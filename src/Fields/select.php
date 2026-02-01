<?php
/**
 * Select Field Template
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

defined( 'ABSPATH' ) || exit;

$options_html = '';
foreach ( $this->getOptions() as $option_value => $option_label ) {
	$options_html .= sprintf(
		'<option value="%s"%s>%s</option>',
		esc_attr( $option_value ),
		selected( $this->getValue(), $option_value, false ),
		esc_html( $option_label )
	);
}

printf(
	'<select id="%s" name="%s"%s%s>%s</select>',
	esc_attr( $this->getFieldId() ),
	esc_attr( $this->getFieldName() ),
	$this->getRequiredAttr(),
	$this->getAttributesString(),
	$options_html
);

