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

class Field {

	private const DEFAULT_TYPE = 'text';
	private const DEFAULT_WRAPPER = 'p';
	private const DEFAULT_HEADING_TAG = 'h6';
	private const DEFAULT_ROWS = 5;
	private const FORM_PREFIX_DELIMITER = '_';

	private const SPECIAL_TYPES = array(
		'textarea',
		'select',
	);

	private const VALID_WRAPPER_TAGS = array(
		'',
		'p',
		'div',
		'span',
		'section',
		'article',
	);

	private array $config;
	private string $field_id;
	private string $field_name_attr;
	private mixed $value;

	/**
	 * Constructor.
	 *
	 * @param array $config Field configuration.
	 * @throws \InvalidArgumentException If required config keys are missing.
	 */
	public function __construct( array $config ) {
		if ( ! isset( $config['name'], $config['entity'], $config['form_prefix'] ) ) {
			throw new \InvalidArgumentException( 'Field config must include name, entity, and form_prefix' );
		}

		$this->config = $config;
		$this->field_id = $this->generateFieldId();
		$this->field_name_attr = $this->generateFieldName();
		$this->value = $this->resolveValue();
	}

	/**
	 * Render the field.
	 *
	 * Outputs the complete field HTML including wrapper, label, input, and help text.
	 */
	public function render(): void {
		$type = $this->getType();

		if ( 'heading' === $type ) {
			$this->renderTemplate( 'heading' );
			return;
		}

		$wrapper = $this->getWrapper();

		if ( $wrapper ) {
			$this->openWrapper( $wrapper );
		}

		$this->renderTemplate( 'label' );
		$this->renderFieldTemplate();
		$this->renderTemplate( 'help' );

		if ( $wrapper ) {
			$this->closeWrapper( $wrapper );
		}
	}

	/**
	 * Open wrapper tag.
	 *
	 * @param string $wrapper Wrapper tag name.
	 */
	protected function openWrapper( string $wrapper ): void {
		if ( ! in_array( $wrapper, self::VALID_WRAPPER_TAGS, true ) ) {
			return;
		}

		printf(
			'<%s>',
			$wrapper
		);
	}

	/**
	 * Close wrapper tag.
	 *
	 * @param string $wrapper Wrapper tag name.
	 */
	protected function closeWrapper( string $wrapper ): void {
		if ( ! in_array( $wrapper, self::VALID_WRAPPER_TAGS, true ) ) {
			return;
		}

		printf(
			'</%s>',
			$wrapper
		);
	}

	/**
	 * Generate field ID attribute.
	 *
	 * @return string Field ID.
	 */
	protected function generateFieldId(): string {
		return sprintf(
			'%s-%s',
			rtrim( $this->config['form_prefix'], self::FORM_PREFIX_DELIMITER ),
			str_replace( self::FORM_PREFIX_DELIMITER, '-', $this->config['name'] )
		);
	}

	/**
	 * Generate field name attribute.
	 *
	 * @return string Field name attribute.
	 */
	protected function generateFieldName(): string {
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
	 *
	 * @return mixed Resolved value.
	 */
	protected function resolveValue(): mixed {
		$value = $this->config['value'] ?? $this->config['default'] ?? '';

		// Handle callable values (closures, array callables, invokable objects)
		if ( is_callable( $value ) ) {
			return $value();
		}

		// Null-safe entity access
		$entity = $this->config['entity'] ?? null;

		// Handle string method names on entity object
		if (
			is_string( $value )
			&& ! empty( trim( $value ) )
			&& is_object( $entity )
			&& method_exists( $entity, $value )
		) {
			return $entity->{$value}();
		}

		// Return static value
		return $value;
	}

	/**
	 * Render template file.
	 *
	 * @param string $template_name Template name.
	 */
	protected function renderTemplate( string $template_name ): void {
		$template_path = $this->getTemplatePath( $template_name );
		if ( file_exists( $template_path ) ) {
			require $template_path;
		}
	}

	/**
	 * Render field template based on type.
	 *
	 * Uses specific templates for textarea and select, generic input template for others.
	 */
	protected function renderFieldTemplate(): void {
		$type = $this->getType();

		$template_name = in_array( $type, self::SPECIAL_TYPES, true )
			? $type
			: 'input';

		$this->renderTemplate( $template_name );
	}

	/**
	 * Get template file path.
	 *
	 * @param string $type Template type.
	 * @return string Template file path.
	 */
	protected function getTemplatePath( string $type ): string {
		if ( isset( $this->config['template_path'] ) ) {
			return $this->config['template_path'];
		}

		$template_base = $this->config['template_base'] ?? __DIR__ . '/Fields';

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
	public function getType(): string {
		return $this->config['type'] ?? self::DEFAULT_TYPE;
	}

	/**
	 * Get field label.
	 *
	 * @return string Field label.
	 */
	public function getLabel(): string {
		return $this->config['label'] ?? '';
	}

	/**
	 * Check if field is required.
	 *
	 * @return bool True if required, false otherwise.
	 */
	public function isRequired(): bool {
		return $this->config['validation']['required'] ?? $this->config['required'] ?? false;
	}

	/**
	 * Get help text.
	 *
	 * @return string Help text.
	 */
	public function getHelp(): string {
		return $this->config['help'] ?? '';
	}

	/**
	 * Get textarea rows.
	 *
	 * @return int Number of rows.
	 */
	public function getRows(): int {
		return $this->config['rows'] ?? self::DEFAULT_ROWS;
	}

	/**
	 * Get select options.
	 *
	 * @return array Options array.
	 */
	public function getOptions(): array {
		return $this->config['options'] ?? array();
	}

	/**
	 * Get custom attributes.
	 *
	 * @return array Attributes array.
	 */
	public function getAttributes(): array {
		return $this->config['attributes'] ?? array();
	}

	/**
	 * Get field ID attribute.
	 *
	 * @return string Field ID.
	 */
	public function getFieldId(): string {
		return $this->field_id;
	}

	/**
	 * Get field name attribute.
	 *
	 * @return string Field name.
	 */
	public function getFieldName(): string {
		return $this->field_name_attr;
	}

	/**
	 * Get field value.
	 *
	 * @return mixed Field value.
	 */
	public function getValue(): mixed {
		return $this->value;
	}

	/**
	 * Get required attribute string.
	 *
	 * @return string Required attribute or empty string.
	 */
	public function getRequiredAttr(): string {
		return $this->isRequired() ? ' required' : '';
	}

	/**
	 * Get attributes as HTML string.
	 *
	 * @return string Attributes string.
	 */
	public function getAttributesString(): string {
		$attributes = $this->getAttributes();
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

	/**
	 * Get wrapper tag.
	 *
	 * @return string Wrapper tag name.
	 */
	public function getWrapper(): string {
		return $this->config['wrapper'] ?? self::DEFAULT_WRAPPER;
	}

	/**
	 * Get heading tag.
	 *
	 * @return string Heading tag name.
	 */
	public function getHeadingTag(): string {
		return $this->config['heading_tag'] ?? self::DEFAULT_HEADING_TAG;
	}
}

