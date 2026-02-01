<?php
/**
 * Label Template
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

defined( 'ABSPATH' ) || exit;

$label = $this->getLabel();

if ( $label ) {
	printf(
		'<label for="%s">%s</label>',
		esc_attr( $this->getFieldId() ),
		esc_html( $label )
	);
}