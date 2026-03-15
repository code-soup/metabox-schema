<?php
/**
 * Validation Example
 *
 * Demonstrates validation rules in schema.
 */

$schema = array(
	'username'  => array(
		'type'       => 'text',
		'label'      => 'Username',
		'validation' => array(
			'required' => true,
			'min'      => 3,
			'max'      => 50,
			'pattern'  => '/^[a-zA-Z0-9_]+$/',
		),
	),
	'age'       => array(
		'type'       => 'number',
		'label'      => 'Age',
		'validation' => array(
			'required' => true,
			'min'      => 18,
			'max'      => 120,
		),
	),
	'birthdate' => array(
		'type'       => 'date',
		'label'      => 'Birth Date',
		'validation' => array(
			'required'    => true,
			'date_format' => 'Y-m-d',
		),
	),
);

