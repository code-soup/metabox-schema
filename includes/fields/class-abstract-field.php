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
	 * @throws \InvalidArgumentException If required config keys are missing.
	 */
	public function __construct( array $config ) {
		if ( ! array_key_exists( 'name', $config ) || ! array_key_exists( 'entity', $config ) || ! array_key_exists( 'form_prefix', $config ) ) {
			throw new \InvalidArgumentException( 'Field config must include name, entity, and form_prefix' );
		}

		$this->validate_string_config( $config['name'], 'Field name' );
		$this->validate_string_config( $config['form_prefix'], 'Form prefix' );

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
	 * @param array $config Raw configuration array.
	 * @return array Sanitized configuration.
	 */
	protected function sanitize_config( array $config ): array {
		$sanitizer = new Config_Sanitizer();
		return $sanitizer->sanitize( $config );
	}

	/**
	 * Generate field ID attribute.
	 *
	 * @return string Field ID.
	 */
	protected function generate_field_id(): string {
		return sprintf(
			'%s-%s',
			rtrim( $this->config['form_prefix'], Constants::FORM_PREFIX_DELIMITER ),
			str_replace( Constants::FORM_PREFIX_DELIMITER, '-', $this->config['name'] )
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
		$value  = $this->config['value'] ?? $this->config['default'] ?? '';
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
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					printf(
						'<!-- Template error in %s: %s -->',
						esc_html( $template_name ),
						esc_html( $e->getMessage() )
					);
				}
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

		$template_base = $this->config['template_base'] ?? __DIR__ . '/fields';
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
			$wrapper // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Validated against VALID_WRAPPER_TAGS whitelist.
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
			$wrapper // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Validated against VALID_WRAPPER_TAGS whitelist.
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

			$parts[] = sprintf(
				'%s="%s"',
				esc_attr( $key ),
				esc_attr( $value )
			);
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
