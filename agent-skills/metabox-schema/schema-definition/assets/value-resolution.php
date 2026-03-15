<?php
/**
 * Value Resolution Example
 *
 * Demonstrates different ways to resolve field values.
 */

// Static value
$schema['status'] = array(
	'type'  => 'text',
	'value' => 'active',
);

// Entity method
$schema['email'] = array(
	'type'  => 'email',
	'value' => 'get_email', // Calls $entity->get_email()
);

// Callable
$schema['created_at'] = array(
	'type'  => 'date',
	'value' => function () {
		return date( 'Y-m-d' );
	},
);

// Default values
$schema['country'] = array(
	'type'    => 'text',
	'default' => 'USA',
);

$schema['registration_date'] = array(
	'type'    => 'date',
	'default' => function () {
		return date( 'Y-m-d' );
	},
);

