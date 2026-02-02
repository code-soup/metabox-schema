<?php
/**
 * Extending Renderer and Field Example
 *
 * Demonstrates how to extend the Renderer and Field classes
 * to customize rendering behavior.
 *
 * WHY EXTEND:
 * - Add custom field types
 * - Customize HTML output
 * - Add wrapper elements
 * - Integrate with CSS frameworks
 * - Add custom attributes or data
 *
 * HOW IT WORKS:
 * All rendering methods are now protected, allowing you to:
 * - Override field creation
 * - Customize grid rendering
 * - Add custom error handling
 * - Modify template resolution
 *
 * @package CodeSoup\MetaboxSchema
 */

require_once __DIR__ . '/../vendor/autoload.php';

use CodeSoup\MetaboxSchema\Renderer;
use CodeSoup\MetaboxSchema\Field;

class BootstrapRenderer extends Renderer {

	protected function openGrid(): void {
		printf( '<div class="row">' );
	}

	protected function closeGrid(): void {
		printf( '</div>' );
	}

	protected function renderField(
		string $field_name,
		array $field_config,
		mixed $entity,
		string $form_prefix,
		?string $template_base
	): void {
		$columns = $field_config['columns'] ?? 12;

		printf(
			'<div class="col-md-%d">',
			$columns
		);

		parent::renderField(
			$field_name,
			$field_config,
			$entity,
			$form_prefix,
			$template_base
		);

		printf( '</div>' );
	}

	protected function handleRenderError( \Exception $e ): void {
		printf(
			'<div class="alert alert-danger">Field rendering error: %s</div>',
			esc_html( $e->getMessage() )
		);
	}
}

class CustomField extends Field {

	protected function openWrapper( string $wrapper ): void {
		if ( 'p' === $wrapper ) {
			printf( '<div class="form-group mb-3">' );
		} else {
			parent::openWrapper( $wrapper );
		}
	}

	protected function closeWrapper( string $wrapper ): void {
		if ( 'p' === $wrapper ) {
			printf( '</div>' );
		} else {
			parent::closeWrapper( $wrapper );
		}
	}

	protected function generateFieldId(): string {
		$id = parent::generateFieldId();
		return 'custom-' . $id;
	}

	public function getAttributesString(): string {
		$attributes = $this->getAttributes();

		$attributes['data-field-name'] = $this->config['name'];

		if ( $this->isRequired() ) {
			$attributes['aria-required'] = 'true';
		}

		$parts = array();
		foreach ( $attributes as $key => $value ) {
			$escaped_key = function_exists( 'esc_attr' )
				? esc_attr( $key )
				: htmlspecialchars( $key, ENT_QUOTES, 'UTF-8' );

			$escaped_value = function_exists( 'esc_attr' )
				? esc_attr( (string) $value )
				: htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8' );

			$parts[] = sprintf(
				'%s="%s"',
				$escaped_key,
				$escaped_value
			);
		}

		return $parts
			? ' ' . implode( ' ', $parts )
			: '';
	}
}

class CustomBootstrapRenderer extends BootstrapRenderer {

	protected function createField(
		string $field_name,
		array $field_config,
		mixed $entity,
		string $form_prefix,
		?string $template_base
	): Field {
		$config = array_merge(
			$field_config,
			array(
				'name'        => $field_name,
				'entity'      => $entity,
				'form_prefix' => $form_prefix,
			)
		);

		if ( $template_base ) {
			$config['template_base'] = $template_base;
		}

		return new CustomField( $config );
	}
}

$schema = array(
	'username' => array(
		'type' => 'text',
		'label' => 'Username',
		'columns' => 6,
		'attributes' => array(
			'placeholder' => 'Enter username',
			'class' => 'form-control',
		),
		'validation' => array(
			'required' => true,
		),
		'help' => 'Choose a unique username',
	),
	'email' => array(
		'type' => 'email',
		'label' => 'Email',
		'columns' => 6,
		'attributes' => array(
			'placeholder' => 'you@example.com',
			'class' => 'form-control',
		),
		'validation' => array(
			'required' => true,
		),
	),
	'bio' => array(
		'type' => 'textarea',
		'label' => 'Biography',
		'columns' => 12,
		'attributes' => array(
			'class' => 'form-control',
		),
		'rows' => 4,
	),
);

echo '<!DOCTYPE html>';
echo '<html>';
echo '<head>';
echo '<title>Custom Renderer Example</title>';
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">';
echo '</head>';
echo '<body>';
echo '<div class="container mt-5">';
echo '<h1>Custom Renderer Example</h1>';
echo '<p>This example demonstrates extending Renderer and Field classes with Bootstrap integration.</p>';

echo '<form>';
CustomBootstrapRenderer::render(
	array(
		'schema' => $schema,
		'entity' => null,
		'form_prefix' => 'custom_form',
	)
);
echo '<button type="submit" class="btn btn-primary">Submit</button>';
echo '</form>';

echo '</div>';
echo '</body>';
echo '</html>';

