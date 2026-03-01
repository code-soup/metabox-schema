<?php
/**
 * Label Template
 *
 * @package CodeSoup\MetaboxSchema
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

defined( 'ABSPATH' ) || exit;

$label = $this->get_label();

if ( $label ) {
	printf(
		'<label for="%s">%s</label>',
		$this->get_field_id(),
		$label
	);
}
