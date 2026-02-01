<?php
/**
 * Test file for CodeSoup Metabox Schema
 *
 * This file demonstrates how to use the package
 */

require_once __DIR__ . '/../vendor/autoload.php';

use CodeSoup\MetaboxSchema\Renderer;

class MockEntity {
	private array $data = array();

	public function __construct( array $data = array() ) {
		$this->data = $data;
	}

	public function getUsername(): string {
		return $this->data['username'] ?? '';
	}

	public function getEmail(): string {
		return $this->data['email'] ?? '';
	}
}

$entity = new MockEntity(
	array(
		'username' => 'johndoe',
		'email'    => 'john@example.com',
	)
);

$schema = array(
	'username' => array(
		'type'       => 'text',
		'label'      => 'Username',
		'attributes' => array(
			'placeholder' => 'Enter username',
			'maxlength'   => 50,
		),
		'validation' => array(
			'required' => true,
			'min'      => 3,
			'max'      => 50,
		),
		'sanitize'   => array( 'trim', 'strip_tags' ),
		'default'    => array(
			'method' => 'getUsername',
		),
		'help'       => 'Enter your username (3-50 characters)',
	),
	'email'    => array(
		'type'       => 'email',
		'label'      => 'Email Address',
		'attributes' => array(
			'placeholder' => 'you@example.com',
		),
		'validation' => array(
			'required' => true,
		),
		'default'    => array(
			'method' => 'getEmail',
		),
	),
);

echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo "<head><title>Metabox Schema Test</title></head>\n";
echo "<body>\n";
echo "<h1>Metabox Schema Test</h1>\n";
echo "<form>\n";

Renderer::render(
	array(
		'schema'      => $schema,
		'entity'      => $entity,
		'form_prefix' => 'test_form',
	)
);

echo "</form>\n";
echo "</body>\n";
echo "</html>\n";

