<?php
/**
 * Custom Input Template - Bootstrap Style
 *
 * This template overrides the default input template.
 * It adds Bootstrap form-control class and wraps in a form-group div.
 *
 * AVAILABLE METHODS:
 * - $this->getFieldId(): Get field ID attribute
 * - $this->getFieldName(): Get field name attribute
 * - $this->getType(): Get input type
 * - $this->getValue(): Get field value
 * - $this->getRequiredAttr(): Get required attribute string
 * - $this->getAttributesString(): Get custom attributes as string
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

echo '<div class="form-group">';

printf(
	'<input id="%s" name="%s" type="%s" value="%s" class="form-control"%s%s />',
	esc_attr( $this->getFieldId() ),
	esc_attr( $this->getFieldName() ),
	esc_attr( $this->getType() ),
	esc_attr( $this->getValue() ),
	$this->getRequiredAttr(),
	$this->getAttributesString()
);

