<?php
/**
 * Field Class
 *
 * Renders individual form fields based on configuration.
 * Supports multiple input types, validation attributes, and custom templates.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema;

/**
 * Field Class.
 *
 * Handles rendering of individual form fields.
 */
class Field {

	use Value_Resolver;

	/**
	 * Field configuration array.
	 *
	 * @var array
	 */
	private array $config;

	/**
	 * Generated field ID attribute.
	 *
	 * @var string
	 */
	private string $field_id;

	/**
	 * Generated field name attribute.
	 *
	 * @var string
	 */
	private string $field_name_attr;

	/**
	 * Resolved field value.
	 *
	 * @var mixed
	 */
	private mixed $value;

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

		$this->config          = $this->sanitize_config( $config );
		$this->field_id        = $this->generate_field_id();
		$this->field_name_attr = $this->generate_field_name();
		$this->value           = $this->resolve_value();
	}

	/**
	 * Sanitize field configuration.
	 *
	 * Sanitizes all configuration values to prevent XSS.
	 * Schema data is sanitized at construction, user values are escaped at output.
	 * Uses Config_Sanitizer for centralized sanitization logic.
	 *
	 * @param array $config Raw configuration array.
	 * @return array Sanitized configuration.
	 */
	protected function sanitize_config( array $config ): array {
		$sanitizer = new Config_Sanitizer();
		return $sanitizer->sanitize( $config );
	}

	/**
	 * Render the field.
	 *
	 * Outputs the complete field HTML including wrapper, label, input, and help text.
	 */
	public function render(): void {
		$type = $this->get_type();

		if ( 'heading' === $type ) {
			$this->render_template( 'heading' );
			return;
		}

		$wrapper = $this->get_wrapper();

		if ( $wrapper ) {
			$this->open_wrapper( $wrapper );
		}

		$this->render_template( 'label' );
		$this->render_field_template();
		$this->render_template( 'help' );

		if ( $wrapper ) {
			$this->close_wrapper( $wrapper );
		}
	}

	/**
	 * Open wrapper tag.
	 *
	 * @param string $wrapper Wrapper tag name.
	 */
	protected function open_wrapper( string $wrapper ): void {
		if ( ! in_array( $wrapper, Constants::VALID_WRAPPER_TAGS, true ) ) {
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
		if ( ! in_array( $wrapper, Constants::VALID_WRAPPER_TAGS, true ) ) {
			return;
		}

		printf(
			'</%s>',
			$wrapper // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Validated against VALID_WRAPPER_TAGS whitelist.
		);
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
	 * Returns static value or calls callable to get dynamic value.
	 * Uses Value_Resolver trait for consistent behavior.
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
			require $template_path;
		}
	}

	/**
	 * Render field template based on type.
	 *
	 * Uses specific templates for textarea and select, generic input template for others.
	 */
	protected function render_field_template(): void {
		$type = $this->get_type();

		$template_name = in_array( $type, Constants::SPECIAL_TYPES, true )
			? $type
			: 'input';

		$this->render_template( $template_name );
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

		return sprintf(
			'%s/%s.php',
			rtrim( $template_base, '/' ),
			$type
		);
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
	 * Check if field is required.
	 *
	 * @return bool True if required, false otherwise.
	 */
	public function is_required(): bool {
		return $this->config['validation']['required'] ?? $this->config['required'] ?? false;
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
	 * Get textarea rows.
	 *
	 * @return int Number of rows.
	 */
	public function get_rows(): int {
		return $this->config['rows'] ?? Constants::DEFAULT_ROWS;
	}

	/**
	 * Get select options.
	 *
	 * @return array Options array.
	 */
	public function get_options(): array {
		return $this->config['options'] ?? array();
	}

	/**
	 * Get custom attributes.
	 *
	 * @return array Attributes array.
	 */
	public function get_attributes(): array {
		return $this->config['attributes'] ?? array();
	}

	/**
	 * Get field ID attribute.
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
	 * Get field value.
	 *
	 * @return mixed Field value.
	 */
	public function get_value(): mixed {
		return $this->value;
	}

	/**
	 * Get escaped value for input/select fields.
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
	 * Get required attribute string.
	 *
	 * @return string Required attribute or empty string.
	 */
	public function get_required_attr(): string {
		return $this->is_required() ? ' required' : '';
	}

	/**
	 * Get attributes as HTML string.
	 *
	 * Excludes 'id' and 'name' attributes as they are handled separately by templates.
	 *
	 * @return string Attributes string.
	 */
	public function get_attributes_string(): string {
		$attributes = $this->get_attributes();
		$parts      = array();

		foreach ( $attributes as $key => $value ) {
			if ( in_array( $key, array( 'id', 'name' ), true ) ) {
				continue;
			}

			$parts[] = sprintf(
				'%s="%s"',
				$key,
				$value
			);
		}

		return $parts
			? ' ' . implode( ' ', $parts )
			: '';
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
	 * Get heading tag.
	 *
	 * @return string Heading tag name.
	 */
	public function get_heading_tag(): string {
		return $this->config['heading_tag'] ?? Constants::DEFAULT_HEADING_TAG;
	}

	/**
	 * Get WP Editor settings.
	 *
	 * @return array Editor settings array.
	 */
	public function get_editor_settings(): array {
		return $this->config['editor_settings'] ?? array();
	}
}
