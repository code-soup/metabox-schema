<?php
/**
 * Heading Field Template
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

defined( 'ABSPATH' ) || exit;

$heading_tag = $this->getHeadingTag();

printf(
	'<%s>%s</%s>',
	$heading_tag,
	esc_html( $this->getLabel() ),
	$heading_tag
);

