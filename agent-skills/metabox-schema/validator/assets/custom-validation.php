<?php
/**
 * Custom Validation Example
 */

use CodeSoup\MetaboxSchema\Validator;

$schema = array(
	'username' => array(
		'type'       => 'text',
		'validation' => array(
			'required' => true,
			'validate' => function ( $value ) {
				if ( username_exists( $value ) ) {
					return 'Username already exists';
				}
				return true;
			},
		),
	),
	'age'      => array(
		'type'       => 'number',
		'validation' => array(
			'required' => true,
			'validate' => function ( $value ) {
				if ( $value < 18 ) {
					return 'Must be 18 or older';
				}
				return true;
			},
		),
	),
);

$validator      = new Validator();
$validated_data = $validator->validate( $_POST, $schema );

