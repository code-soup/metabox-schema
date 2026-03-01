<?php
/**
 * Renderer Class
 *
 * Renders form fields from schema definition.
 * Handles field iteration, grid layout, and error handling.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema;

/**
 * Renderer Class.
 *
 * Handles rendering of form fields from schema definitions.
 */
class Renderer {

	/**
	 * Render schema fields.
	 *
	 * @param array $config Configuration array with schema, entity, and form_prefix.
	 */
	public static function render( array $config ): void {
		$instance = new static();
		$instance->render_fields( $config );
	}

	/**
	 * Render fields from configuration.
	 *
	 * @param array $config Configuration array with schema, entity, and form_prefix.
	 */
	protected function render_fields( array $config ): void {
		$schema        = $config['schema'];
		$entity        = $config['entity'] ?? null;
		$form_prefix   = $config['form_prefix'];
		$template_base = $config['template_base'] ?? null;

		if ( isset( $config['values'] ) && is_array( $config['values'] ) ) {
			$schema = $this->map_values_to_schema(
				$schema,
				$config['values']
			);
		}

		foreach ( $schema as $field_name => $field_config ) {
			$this->render_field(
				$field_name,
				$field_config,
				$entity,
				$form_prefix,
				$template_base
			);
		}
	}

	/**
	 * Render single field.
	 *
	 * @param string      $field_name    Field name.
	 * @param array       $field_config  Field configuration.
	 * @param mixed       $entity        Entity object.
	 * @param string      $form_prefix   Form prefix.
	 * @param string|null $template_base Template base directory.
	 */
	protected function render_field(
		string $field_name,
		array $field_config,
		mixed $entity,
		string $form_prefix,
		?string $template_base
	): void {
		$grid = $field_config['grid'] ?? false;

		if ( 'start' === $grid ) {
			$this->open_grid();
		}

		ob_start();

		try {
			$field = $this->create_field(
				$field_name,
				$field_config,
				$entity,
				$form_prefix,
				$template_base
			);
			$field->render();

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Template output already sanitized in Field class.
			echo ob_get_clean();
		} catch ( \Exception $e ) {
			ob_end_clean();
			$this->handle_render_error( $e );
		}

		if ( 'end' === $grid ) {
			$this->close_grid();
		}
	}

	/**
	 * Map values array to schema fields.
	 *
	 * @param array $schema Schema definition.
	 * @param array $values Values to map (field_name => value).
	 * @return array Modified schema with values mapped.
	 */
	protected function map_values_to_schema( array $schema, array $values ): array {
		foreach ( $values as $key => $value ) {
			if ( isset( $schema[ $key ] ) ) {
				$schema[ $key ]['value'] = $value;
			}
		}

		return $schema;
	}

	/**
	 * Create field instance.
	 *
	 * @param string      $field_name    Field name.
	 * @param array       $field_config  Field configuration.
	 * @param mixed       $entity        Entity object.
	 * @param string      $form_prefix   Form prefix.
	 * @param string|null $template_base Template base directory.
	 * @return Field Field instance.
	 */
	protected function create_field(
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

		return new Field( $config );
	}

	/**
	 * Open grid wrapper.
	 */
	protected function open_grid(): void {
		printf( '<div class="grid">' );
	}

	/**
	 * Close grid wrapper.
	 */
	protected function close_grid(): void {
		printf( '</div>' );
	}

	/**
	 * Handle rendering error.
	 *
	 * @param \Exception $e Exception.
	 */
	protected function handle_render_error( \Exception $e ): void {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			printf(
				'<!-- Field rendering error: %s -->',
				esc_html( $e->getMessage() )
			);
		}
	}
}
