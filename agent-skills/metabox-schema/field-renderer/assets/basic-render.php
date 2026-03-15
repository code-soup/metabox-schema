<?php
/**
 * Basic Rendering Example
 */

use CodeSoup\MetaboxSchema\Renderer;

$schema = array(
	'username' => array(
		'type'  => 'text',
		'label' => 'Username',
	),
	'email'    => array(
		'type'  => 'email',
		'label' => 'Email',
	),
);

Renderer::render(
	array(
		'schema'      => $schema,
		'entity'      => $entity,
		'form_prefix' => 'user_form',
	)
);

