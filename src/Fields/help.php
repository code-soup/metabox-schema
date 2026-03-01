<?php
/**
 * Help Text Template
 *
 * @package CodeSoup\MetaboxSchema
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

defined( 'ABSPATH' ) || exit;

$help = $this->get_help();

if ( $help ) {
	$allowed_html = array(
		'a'      => array(
			'href'   => array(),
			'title'  => array(),
			'target' => array(),
		),
		'strong' => array(),
		'em'     => array(),
		'code'   => array(),
	);

	printf(
		'<small>%s</small>',
		wp_kses( $help, $allowed_html )
	);
}
