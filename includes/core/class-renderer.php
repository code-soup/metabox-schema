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

	use Debug_Helper;

	/**
	 * Instance-based custom field type registry.
	 *
	 * @var array<string, string>
	 */
	protected array $custom_field_types = array();

	/**
	 * Render schema fields (static facade).
	 *
	 * @param array $config Configuration array with schema, entity, and form_prefix.
	 */
	public static function render( array $config ): void {
		$instance = new static();
		$instance->render_fields( $config );
	}

	/**
	 * Register a custom field type for this renderer instance.
	 *
	 * @param string $type Field type name.
	 * @param string $class_name Fully qualified class name extending Abstract_Field.
	 * @throws \InvalidArgumentException If type is empty or class doesn't extend Abstract_Field.
	 */
	public function register_field_type( string $type, string $class_name ): void {
		if ( empty( $type ) ) {
			throw new \InvalidArgumentException( 'Field type cannot be empty' );
		}

		if ( ! class_exists( $class_name ) ) {
			throw new \InvalidArgumentException(
				sprintf(
					'Class %s does not exist',
					$class_name // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception for developers.
				)
			);
		}

		if ( ! is_subclass_of( $class_name, Abstract_Field::class ) ) {
			throw new \InvalidArgumentException(
				sprintf(
					'Class %s must extend %s',
					$class_name, // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception for developers.
					Abstract_Field::class // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception for developers.
				)
			);
		}

		$this->custom_field_types[ $type ] = $class_name;
	}

	/**
	 * Render fields from configuration.
	 *
	 * Public method allows direct instance usage or extension.
	 *
	 * @param array $config Configuration array with schema, entity, and form_prefix.
	 * @throws \InvalidArgumentException If required config keys are missing.
	 */
	public function render_fields( array $config ): void {
		if ( ! isset( $config['schema'] ) || ! is_array( $config['schema'] ) ) {
			throw new \InvalidArgumentException( 'Renderer config must include schema array' );
		}

		if ( ! isset( $config['form_prefix'] ) || ! is_string( $config['form_prefix'] ) ) {
			throw new \InvalidArgumentException( 'Renderer config must include form_prefix string' );
		}

		// Validate entity type.
		if ( isset( $config['entity'] ) && null !== $config['entity'] && ! is_object( $config['entity'] ) ) {
			throw new \InvalidArgumentException( 'Renderer config entity must be object or null' );
		}

		// Validate schema structure.
		foreach ( $config['schema'] as $field_name => $field_config ) {
			if ( ! is_string( $field_name ) ) {
				throw new \InvalidArgumentException(
					sprintf(
						'Field name must be a string, %s given',
						gettype( $field_name ) // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception for developers.
					)
				);
			}

			if ( ! is_array( $field_config ) ) {
				throw new \InvalidArgumentException(
					sprintf(
						'Field config for "%s" must be an array, %s given',
						$field_name, // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception for developers.
						gettype( $field_config ) // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception for developers.
					)
				);
			}
		}

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

		$grid_is_open = false;

		// Buffer entire form output for better performance.
		ob_start();

		try {
			foreach ( $schema as $field_name => $field_config ) {
				$grid_is_open = $this->render_field(
					$field_name,
					$field_config,
					$entity,
					$form_prefix,
					$template_base,
					$grid_is_open
				);
			}

			if ( $grid_is_open ) {
				$this->maybe_trigger_error(
					'Grid was not explicitly closed in schema. Auto-closing grid.',
					E_USER_NOTICE
				);
				$this->close_grid();
			}

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Field templates handle escaping.
			echo ob_get_clean();
		} catch ( \Exception $e ) {
			ob_end_clean();
			throw $e;
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
	 * @param bool        $grid_is_open  Whether grid is currently open.
	 * @return bool Whether grid is open after rendering this field.
	 */
	protected function render_field(
		string $field_name,
		array $field_config,
		mixed $entity,
		string $form_prefix,
		?string $template_base,
		bool $grid_is_open = false
	): bool {
		$grid = $field_config['grid'] ?? false;

		if ( 'start' === $grid ) {
			$this->open_grid();
			$grid_is_open = true;
		}

		try {
			$field = $this->create_field(
				$field_name,
				$field_config,
				$entity,
				$form_prefix,
				$template_base
			);
			$field->render();
		} catch ( \Exception $e ) {
			$this->handle_render_error( $e );
		}

		if ( 'end' === $grid ) {
			$this->close_grid();
			$grid_is_open = false;
		}

		return $grid_is_open;
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
	 * Checks instance registry first, then falls back to Field_Factory.
	 *
	 * @param string      $field_name    Field name.
	 * @param array       $field_config  Field configuration.
	 * @param mixed       $entity        Entity object.
	 * @param string      $form_prefix   Form prefix.
	 * @param string|null $template_base Template base directory.
	 * @return Abstract_Field Field instance.
	 */
	protected function create_field(
		string $field_name,
		array $field_config,
		mixed $entity,
		string $form_prefix,
		?string $template_base
	): Abstract_Field {
		$type = $field_config['type'] ?? Constants::DEFAULT_TYPE;

		// Check instance registry first.
		if ( isset( $this->custom_field_types[ $type ] ) ) {
			return $this->create_custom_field(
				$this->custom_field_types[ $type ],
				$field_name,
				$field_config,
				$entity,
				$form_prefix,
				$template_base
			);
		}

		// Fall back to Field_Factory for built-in types.
		return Field_Factory::create(
			$field_name,
			$field_config,
			$entity,
			$form_prefix,
			$template_base
		);
	}

	/**
	 * Create custom field instance from registry.
	 *
	 * @param string      $class_name    Field class name.
	 * @param string      $field_name    Field name.
	 * @param array       $field_config  Field configuration.
	 * @param mixed       $entity        Entity object.
	 * @param string      $form_prefix   Form prefix.
	 * @param string|null $template_base Template base directory.
	 * @return Abstract_Field Field instance.
	 */
	protected function create_custom_field(
		string $class_name,
		string $field_name,
		array $field_config,
		mixed $entity,
		string $form_prefix,
		?string $template_base
	): Abstract_Field {
		$config = array_merge(
			$field_config,
			array(
				'name'        => $field_name,
				'entity'      => $entity,
				'form_prefix' => $form_prefix,
			)
		);

		if ( null !== $template_base ) {
			$config['template_base'] = $template_base;
		}

		return new $class_name( $config );
	}

	/**
	 * Get grid wrapper class name.
	 *
	 * Override this method to customize the grid class.
	 *
	 * @return string Grid class name.
	 */
	protected function get_grid_class(): string {
		return Constants::DEFAULT_GRID_CLASS;
	}

	/**
	 * Open grid wrapper.
	 */
	protected function open_grid(): void {
		printf( '<div class="%s">', esc_attr( $this->get_grid_class() ) );
	}

	/**
	 * Close grid wrapper.
	 */
	protected function close_grid(): void {
		echo '</div>';
	}

	/**
	 * Handle rendering error.
	 *
	 * @param \Exception $e Exception.
	 */
	protected function handle_render_error( \Exception $e ): void {
		$this->maybe_output_debug_comment(
			sprintf( 'Field rendering error: %s', $e->getMessage() )
		);
	}
}
