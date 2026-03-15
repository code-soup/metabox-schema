<?php
/**
 * Custom Template Rendering Example
 */

use CodeSoup\MetaboxSchema\Renderer;

$schema = array(
	'bio' => array(
		'type'  => 'textarea',
		'label' => 'Biography',
	),
);

Renderer::render(
	array(
		'schema'        => $schema,
		'entity'        => $entity,
		'form_prefix'   => 'user_form',
		'template_base' => __DIR__ . '/templates',
	)
);

