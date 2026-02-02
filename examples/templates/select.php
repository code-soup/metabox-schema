<?php
/**
 * Custom Select Template - Bootstrap Style
 *
 * This template overrides the default select template.
 * It adds Bootstrap form-select class.
 *
 * AVAILABLE METHODS:
 * - $this->getFieldId(): Get field ID attribute
 * - $this->getFieldName(): Get field name attribute
 * - $this->getValue(): Get current value
 * - $this->getOptions(): Get options array
 * - $this->getRequiredAttr(): Get required attribute string
 * - $this->getAttributesString(): Get custom attributes as string
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

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
	'<select id="%s" name="%s" class="form-control"%s%s>%s</select>',
	esc_attr( $this->getFieldId() ),
	esc_attr( $this->getFieldName() ),
	$this->getRequiredAttr(),
	$this->getAttributesString(),
	$options_html
);

