<?php
/**
 * Textarea Field Template
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

defined( 'ABSPATH' ) || exit;

printf(
	'<textarea id="%s" name="%s" rows="%s"%s%s>%s</textarea>',
	esc_attr( $this->getFieldId() ),
	esc_attr( $this->getFieldName() ),
	esc_attr( (string) $this->getRows() ),
	$this->getRequiredAttr(),
	$this->getAttributesString(),
	esc_textarea( $this->getValue() )
);

