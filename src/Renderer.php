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

class Renderer {

	/**
	 * Render schema fields.
	 *
	 * @param array $config Configuration array with schema, entity, and form_prefix.
	 */
	public static function render( array $config ): void {
		$instance = new static();
		$instance->renderFields( $config );
	}

	/**
	 * Render fields from configuration.
	 *
	 * @param array $config Configuration array with schema, entity, and form_prefix.
	 */
	protected function renderFields( array $config ): void {
		$schema = $config['schema'];
		$entity = $config['entity'];
		$form_prefix = $config['form_prefix'];
		$template_base = $config['template_base'] ?? null;

		foreach ( $schema as $field_name => $field_config ) {
			$this->renderField(
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
	protected function renderField(
		string $field_name,
		array $field_config,
		mixed $entity,
		string $form_prefix,
		?string $template_base
	): void {
		$grid = $field_config['grid'] ?? false;

		if ( 'start' === $grid ) {
			$this->openGrid();
		}

		ob_start();

		try {
			$field = $this->createField(
				$field_name,
				$field_config,
				$entity,
				$form_prefix,
				$template_base
			);
			$field->render();

			echo ob_get_clean();
		} catch ( \Exception $e ) {
			ob_end_clean();
			$this->handleRenderError( $e );
		}

		if ( 'end' === $grid ) {
			$this->closeGrid();
		}
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

		return new Field( $config );
	}

	/**
	 * Open grid wrapper.
	 */
	protected function openGrid(): void {
		printf( '<div class="grid">' );
	}

	/**
	 * Close grid wrapper.
	 */
	protected function closeGrid(): void {
		printf( '</div>' );
	}

	/**
	 * Handle rendering error.
	 *
	 * @param \Exception $e Exception.
	 */
	protected function handleRenderError( \Exception $e ): void {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			printf(
				'<!-- Field rendering error: %s -->',
				esc_html( $e->getMessage() )
			);
		}
	}
}

