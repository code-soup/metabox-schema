<?php
/**
 * Basic Validation Example
 */

use CodeSoup\MetaboxSchema\Validator;

$schema = array(
	'username' => array(
		'type'       => 'text',
		'validation' => array(
			'required' => true,
			'min'      => 3,
			'max'      => 50,
		),
	),
	'email'    => array(
		'type'       => 'email',
		'validation' => array(
			'required' => true,
		),
	),
);

$validator       = new Validator();
$validated_data  = $validator->validate( $_POST, $schema );

if ( $validator->has_errors() ) {
	foreach ( $validator->get_errors() as $field => $error ) {
		echo $error;
	}
} else {
	// Use $validated_data (already sanitized)
}

