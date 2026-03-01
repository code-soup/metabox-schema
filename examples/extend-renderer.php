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

if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'sanitize_key' ) ) {
	function sanitize_key( $key ) {
		return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $key ) );
	}
}

if ( ! function_exists( 'sanitize_text_field' ) ) {
	function sanitize_text_field( $str ) {
		return strip_tags( (string) $str );
	}
}

if ( ! function_exists( 'absint' ) ) {
	function absint( $maybeint ) {
		return abs( (int) $maybeint );
	}
}

if ( ! function_exists( 'esc_attr' ) ) {
	function esc_attr( $text ) {
		return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
	}
}

class Bootstrap_Renderer extends Renderer {

	protected function open_grid(): void {
		printf( '<div class="row">' );
	}

	protected function close_grid(): void {
		printf( '</div>' );
	}

	protected function render_field(
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

		parent::render_field(
			$field_name,
			$field_config,
			$entity,
			$form_prefix,
			$template_base
		);

		printf( '</div>' );
	}

	protected function handle_render_error( \Exception $e ): void {
		printf(
			'<div class="alert alert-danger">Field rendering error: %s</div>',
			esc_html( $e->getMessage() )
		);
	}
}

class Custom_Field extends Field {

	protected function open_wrapper( string $wrapper ): void {
		if ( 'p' === $wrapper ) {
			printf( '<div class="form-group mb-3">' );
		} else {
			parent::open_wrapper( $wrapper );
		}
	}

	protected function close_wrapper( string $wrapper ): void {
		if ( 'p' === $wrapper ) {
			printf( '</div>' );
		} else {
			parent::close_wrapper( $wrapper );
		}
	}

	protected function generate_field_id(): string {
		$id = parent::generate_field_id();
		return 'custom-' . $id;
	}

	public function get_attributes_string(): string {
		$attributes = $this->get_attributes();

		$attributes['data-field-name'] = $this->config['name'];

		if ( $this->is_required() ) {
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
		'value' => 'john_doe',
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
		'value' => 'john@example.com',
	),
	'bio' => array(
		'type' => 'textarea',
		'label' => 'Biography',
		'columns' => 12,
		'attributes' => array(
			'class' => 'form-control',
		),
		'rows' => 4,
		'value' => 'Software developer passionate about clean code.',
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
Bootstrap_Renderer::render(
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

