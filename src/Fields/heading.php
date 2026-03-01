<?php
/**
 * Heading Field Template
 *
 * @package CodeSoup\MetaboxSchema
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

defined( 'ABSPATH' ) || exit;

printf(
	'<%s>%s</%s>',
	$this->get_heading_tag(),
	$this->get_label(),
	$this->get_heading_tag()
);
