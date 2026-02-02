<?php
/**
 * Custom Textarea Template - Bootstrap Style
 *
 * This template overrides the default textarea template.
 * It adds Bootstrap form-control class.
 *
 * AVAILABLE METHODS:
 * - $this->getFieldId(): Get field ID attribute
 * - $this->getFieldName(): Get field name attribute
 * - $this->getValue(): Get field value
 * - $this->getRows(): Get number of rows
 * - $this->getRequiredAttr(): Get required attribute string
 * - $this->getAttributesString(): Get custom attributes as string
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

printf(
	'<textarea id="%s" name="%s" rows="%d" class="form-control"%s%s>%s</textarea>',
	esc_attr( $this->getFieldId() ),
	esc_attr( $this->getFieldName() ),
	$this->getRows(),
	$this->getRequiredAttr(),
	$this->getAttributesString(),
	esc_textarea( $this->getValue() )
);

