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
		$schema = $config['schema'];
		$entity = $config['entity'];
		$form_prefix = $config['form_prefix'];

		foreach ( $schema as $field_name => $field_config ) {
			$grid = $field_config['grid'] ?? false;

			if ( 'start' === $grid ) {
				printf( '<div class="grid">' );
			}

			ob_start();

			try {
				$field = new Field(
					array_merge(
						$field_config,
						array(
							'name'        => $field_name,
							'entity'      => $entity,
							'form_prefix' => $form_prefix,
						)
					)
				);
				$field->render();

				echo ob_get_clean();
			} catch ( \Exception $e ) {
				ob_end_clean();

				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					printf(
						'<!-- Field rendering error: %s -->',
						esc_html( $e->getMessage() )
					);
				}
			}

			if ( 'end' === $grid ) {
				printf( '</div>' );
			}
		}
	}
}

