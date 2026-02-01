<?php
/**
 * Input Field Template
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

defined( 'ABSPATH' ) || exit;

printf(
	'<input id="%s" name="%s" type="%s" value="%s"%s%s />',
	esc_attr( $this->getFieldId() ),
	esc_attr( $this->getFieldName() ),
	esc_attr( $this->getType() ),
	esc_attr( $this->getValue() ),
	$this->getRequiredAttr(),
	$this->getAttributesString()
);

