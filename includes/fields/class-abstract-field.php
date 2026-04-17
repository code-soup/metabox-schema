<?php
/**
 * Abstract Field Class
 *
 * Base class for all field types.
 * Provides common functionality for field rendering, value resolution, and configuration.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema;

/**
 * Abstract Field Class.
 *
 * Base class that all field types must extend.
 */
abstract class Abstract_Field {

	use Value_Resolver;
	use Debug_Helper;

	/**
	 * Shared sanitizer instance for all fields.
	 *
	 * @var Config_Sanitizer|null
	 */
	private static ?Config_Sanitizer $sanitizer = null;

	/**
	 * Field configuration array.
	 *
	 * @var array
	 */
	protected array $config;

	/**
	 * Generated field ID attribute.
	 *
	 * @var string
	 */
	protected string $field_id;

	/**
	 * Generated field name attribute.
	 *
	 * @var string
	 */
	protected string $field_name_attr;

	/**
	 * Resolved field value.
	 *
	 * @var mixed
	 */
	protected mixed $value;

	/**
	 * Constructor.
	 *
	 * @param array $config Field configuration.
	 * @throws \InvalidArgumentException If required config keys are missing or invalid types.
	 */
	public function __construct( array $config ) {
		if ( ! array_key_exists( 'name', $config ) || ! array_key_exists( 'entity', $config ) || ! array_key_exists( 'form_prefix', $config ) ) {
			throw new \InvalidArgumentException( 'Field config must include name, entity, and form_prefix' );
		}

		$this->validate_string_config( $config['name'], 'Field name' );
		$this->validate_string_config( $config['form_prefix'], 'Form prefix' );

		// Validate field name format.
		if ( ! preg_match( '/^[a-zA-Z0-9_-]+$/', $config['name'] ) ) {
			throw new \InvalidArgumentException(
				sprintf(
					'Field name "%s" contains invalid characters. Use only alphanumeric, hyphens, and underscores.',
					$config['name'] // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception for developers.
				)
			);
		}

		// Validate form_prefix format.
		if ( empty( trim( $config['form_prefix'] ) ) ) {
			throw new \InvalidArgumentException( 'Form prefix cannot be empty' );
		}

		if ( ! preg_match( '/^[a-zA-Z0-9_-]+$/', $config['form_prefix'] ) ) {
			throw new \InvalidArgumentException(
				sprintf(
					'Form prefix "%s" contains invalid characters. Use only alphanumeric, hyphens, and underscores.',
					$config['form_prefix'] // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception for developers.
				)
			);
		}

		// Validate entity type.
		if ( null !== $config['entity'] && ! is_object( $config['entity'] ) ) {
			throw new \InvalidArgumentException( 'Field entity must be an object or null' );
		}

		$this->config          = $this->sanitize_config( $config );
		$this->field_id        = $this->generate_field_id();
		$this->field_name_attr = $this->generate_field_name();
		$this->value           = $this->resolve_value();
	}

	/**
	 * Validate string config value.
	 *
	 * @param mixed  $value Value to validate.
	 * @param string $label Label for error message.
	 * @throws \InvalidArgumentException If value is not a non-empty string.
	 */
	private function validate_string_config( $value, string $label ): void {
		if ( ! is_string( $value ) || '' === trim( $value ) ) {
			throw new \InvalidArgumentException(
				sprintf(
					'%s must be a non-empty string',
					$label // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception messages are for developers, not output.
				)
			);
		}
	}

	/**
	 * Render the field.
	 *
	 * Default implementation renders wrapper, label, field template, and help.
	 * Override in child classes for custom rendering logic.
	 */
	public function render(): void {
		$wrapper = $this->get_wrapper();

		if ( $wrapper ) {
			$this->open_wrapper( $wrapper );
		}

		$this->render_template( 'label' );
		$this->render_template( $this->get_template_name() );
		$this->render_template( 'help' );

		if ( $wrapper ) {
			$this->close_wrapper( $wrapper );
		}
	}

	/**
	 * Get the template name for this field type.
	 *
	 * @return string Template name (e.g., 'input', 'media', 'select').
	 */
	abstract protected function get_template_name(): string;

	/**
	 * Sanitize field configuration.
	 *
	 * Uses shared sanitizer instance for better performance.
	 *
	 * @param array $config Raw configuration array.
	 * @return array Sanitized configuration.
	 */
	protected function sanitize_config( array $config ): array {
		if ( null === self::$sanitizer ) {
			self::$sanitizer = new Config_Sanitizer();
		}
		return self::$sanitizer->sanitize( $config );
	}

	/**
	 * Generate field ID attribute.
	 *
	 * Forces dashes in HTML IDs for consistency and HTML spec compliance.
	 * Strips trailing underscore from form_prefix and converts all underscores to dashes.
	 *
	 * @return string Field ID.
	 */
	protected function generate_field_id(): string {
		$prefix = rtrim( $this->config['form_prefix'], '_' );
		$prefix = str_replace( '_', '-', $prefix );
		$name   = str_replace( '_', '-', $this->config['name'] );

		return sprintf(
			'%s-%s',
			$prefix,
			$name
		);
	}

	/**
	 * Generate field name attribute.
	 *
	 * @return string Field name attribute.
	 */
	protected function generate_field_name(): string {
		return sprintf(
			'%s[%s]',
			$this->config['form_prefix'],
			$this->config['name']
		);
	}

	/**
	 * Resolve field value.
	 *
	 * @return mixed Resolved value.
	 */
	protected function resolve_value(): mixed {
		if ( array_key_exists( 'value', $this->config ) ) {
			$value = $this->config['value'];
		} elseif ( array_key_exists( 'default', $this->config ) ) {
			$value = $this->config['default'];
		} else {
			$value = '';
		}

		$entity = $this->config['entity'] ?? null;

		$value = $this->resolve_callable( $value );
		$value = $this->resolve_entity_method( $value, $entity );

		return $value;
	}

	/**
	 * Render template file.
	 *
	 * @param string $template_name Template name.
	 */
	protected function render_template( string $template_name ): void {
		$template_path = $this->get_template_path( $template_name );
		if ( file_exists( $template_path ) ) {
			try {
				require $template_path;
			} catch ( \Throwable $e ) {
				$this->maybe_output_debug_comment(
					sprintf( 'Template error in %s: %s', $template_name, $e->getMessage() )
				);
			}
		}
	}

	/**
	 * Get template file path.
	 *
	 * @param string $type Template type.
	 * @return string Template file path.
	 */
	protected function get_template_path( string $type ): string {
		if ( isset( $this->config['template_path'] ) ) {
			return $this->config['template_path'];
		}

		$template_base = $this->config['template_base'] ?? __DIR__;
		$base_path     = rtrim( $template_base, '/' );

		$template_file = sprintf( '%s/%s/template.php', $base_path, $type );
		if ( file_exists( $template_file ) ) {
			return $template_file;
		}

		return '';
	}

	/**
	 * Check if wrapper tag is valid.
	 *
	 * @param string $wrapper Wrapper tag name.
	 * @return bool True if valid.
	 */
	private function is_valid_wrapper( string $wrapper ): bool {
		return in_array( $wrapper, Constants::VALID_WRAPPER_TAGS, true );
	}

	/**
	 * Open wrapper tag.
	 *
	 * @param string $wrapper Wrapper tag name.
	 */
	protected function open_wrapper( string $wrapper ): void {
		if ( ! $this->is_valid_wrapper( $wrapper ) ) {
			return;
		}

		printf(
			'<%s>',
			esc_html( $wrapper )
		);
	}

	/**
	 * Close wrapper tag.
	 *
	 * @param string $wrapper Wrapper tag name.
	 */
	protected function close_wrapper( string $wrapper ): void {
		if ( ! $this->is_valid_wrapper( $wrapper ) ) {
			return;
		}

		printf(
			'</%s>',
			esc_html( $wrapper )
		);
	}

	/**
	 * Get field ID.
	 *
	 * @return string Field ID.
	 */
	public function get_field_id(): string {
		return $this->field_id;
	}

	/**
	 * Get field name attribute.
	 *
	 * @return string Field name.
	 */
	public function get_field_name(): string {
		return $this->field_name_attr;
	}

	/**
	 * Get field type.
	 *
	 * @return string Field type.
	 */
	public function get_type(): string {
		return $this->config['type'] ?? Constants::DEFAULT_TYPE;
	}

	/**
	 * Get field label.
	 *
	 * @return string Field label.
	 */
	public function get_label(): string {
		return $this->config['label'] ?? '';
	}

	/**
	 * Get field value.
	 *
	 * @return mixed Field value.
	 */
	public function get_value(): mixed {
		return $this->value;
	}

	/**
	 * Get escaped value for input fields.
	 *
	 * @return string Escaped value.
	 */
	public function get_escaped_value(): string {
		return esc_attr( (string) $this->value );
	}

	/**
	 * Get escaped value for textarea fields.
	 *
	 * @return string Escaped textarea value.
	 */
	public function get_escaped_textarea_value(): string {
		return esc_textarea( (string) $this->value );
	}

	/**
	 * Check if field is required.
	 *
	 * @return bool True if required.
	 */
	public function is_required(): bool {
		$validation = $this->config['validation'] ?? array();
		return ! empty( $validation['required'] );
	}

	/**
	 * Get required attribute string.
	 *
	 * @return string Required attribute or empty string.
	 */
	public function get_required_attr(): string {
		return $this->is_required() ? ' required' : '';
	}

	/**
	 * Get field attributes.
	 *
	 * @return array Field attributes.
	 */
	public function get_attributes(): array {
		return $this->config['attributes'] ?? array();
	}

	/**
	 * Get attributes as HTML string.
	 *
	 * @return string Attributes string.
	 */
	public function get_attributes_string(): string {
		$attributes = $this->get_attributes();
		$parts      = array();

		foreach ( $attributes as $key => $value ) {
			if ( in_array( $key, Constants::RESERVED_ATTRIBUTES, true ) ) {
				continue;
			}

			$parts[] = esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}

		return $parts
			? ' ' . implode( ' ', $parts )
			: '';
	}

	/**
	 * Get help text.
	 *
	 * @return string Help text.
	 */
	public function get_help(): string {
		return $this->config['help'] ?? '';
	}

	/**
	 * Get wrapper tag.
	 *
	 * @return string Wrapper tag name.
	 */
	public function get_wrapper(): string {
		return $this->config['wrapper'] ?? Constants::DEFAULT_WRAPPER;
	}

	/**
	 * Get number of rows for textarea/editor fields.
	 *
	 * @return int Number of rows.
	 */
	public function get_rows(): int {
		return $this->config['rows'] ?? Constants::DEFAULT_ROWS;
	}
}
