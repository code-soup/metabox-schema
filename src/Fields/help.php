<?php
/**
 * Help Text Template
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

defined( 'ABSPATH' ) || exit;

$help = $this->getHelp();

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

	$sanitized_help = function_exists( 'wp_kses' )
		? wp_kses( $help, $allowed_html )
		: strip_tags( $help, '<a><strong><em><code>' );

	printf(
		'<small>%s</small>',
		$sanitized_help
	);
}

