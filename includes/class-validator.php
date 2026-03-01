<?php
/**
 * Validator Class
 *
 * Validates and sanitizes form data against a schema definition.
 * Supports validation rules, custom error messages, and multiple sanitization callbacks.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema;

/**
 * Validator Class.
 *
 * Handles validation and sanitization of form data.
 */
class Validator {

	use Value_Resolver;

	/**
	 * Validation errors array.
	 *
	 * @var array
	 */
	private array $errors = array();

	/**
	 * Validate data against schema.
	 *
	 * Processes each field in the schema, applying sanitization and validation rules.
	 * Returns validated data with defaults for invalid fields.
	 *
	 * @param array $data   Raw input data to validate.
	 * @param array $schema Schema definition with field configurations.
	 * @return array Validated and sanitized data.
	 */
	public function validate( array $data, array $schema ): array {
		$this->errors = array();
		$validated    = array();

		foreach ( $schema as $field_name => $field_config ) {
			$type = $field_config['type'] ?? Constants::DEFAULT_TYPE;

			if ( in_array( $type, Constants::SKIP_VALIDATION_TYPES, true ) ) {
				continue;
			}

			$context = $this->build_field_context( $field_name, $field_config );
			$value   = $data[ $field_name ] ?? null;

			if ( $this->is_empty_value( $value ) ) {
				$validated[ $field_name ] = $this->handle_empty_value( $field_name, $context );
				continue;
			}

			$sanitized = $this->sanitize_value( $value, $field_config );

			if ( $context['is_required'] && $this->is_empty_value( $sanitized ) ) {
				$this->errors[ $field_name ] = $this->get_required_error( $context );
				$validated[ $field_name ]    = $this->resolve_default( $field_config );
				continue;
			}

			$validation_result = $this->validate_value( $sanitized, $context );

			if ( true !== $validation_result ) {
				$this->errors[ $field_name ] = $validation_result;
				$validated[ $field_name ]    = $this->resolve_default( $field_config );
				continue;
			}

			$validated[ $field_name ] = $sanitized;
		}

		return $validated;
	}

	/**
	 * Get validation errors.
	 *
	 * @return array Array of field names to error messages.
	 */
	public function get_errors(): array {
		return $this->errors;
	}

	/**
	 * Check if validation has errors.
	 *
	 * @return bool True if errors exist, false otherwise.
	 */
	public function has_errors(): bool {
		return ! empty( $this->errors );
	}

	/**
	 * Build field context object.
	 *
	 * Extracts and organizes field configuration into a context array
	 * to avoid repeated config access.
	 *
	 * @param string $field_name   Field name.
	 * @param array  $field_config Field configuration.
	 * @return array Field context with label, validation, errors, type.
	 */
	protected function build_field_context( string $field_name, array $field_config ): array {
		$validation = $field_config['validation'] ?? array();

		return array(
			'field_name'  => $field_name,
			'label'       => $field_config['label'] ?? String_Formatter::format_field_name( $field_name ),
			'validation'  => $validation,
			'errors'      => $field_config['errors'] ?? array(),
			'type'        => $field_config['type'] ?? Constants::DEFAULT_TYPE,
			'is_required' => $validation['required'] ?? false,
			'options'     => $field_config['options'] ?? array(),
		);
	}

	/**
	 * Check if value is considered empty.
	 *
	 * Treats null and empty string as empty, but allows 0, '0', and false.
	 *
	 * @param mixed $value Value to check.
	 * @return bool True if empty, false otherwise.
	 */
	protected function is_empty_value( $value ): bool {
		return null === $value || '' === $value;
	}

	/**
	 * Handle empty value validation.
	 *
	 * @param string $field_name Field name.
	 * @param array  $context    Field context.
	 * @return mixed Default value or empty string.
	 */
	protected function handle_empty_value( string $field_name, array $context ) {
		if ( $context['is_required'] ) {
			$this->errors[ $field_name ] = $this->get_required_error( $context );
		}

		return $this->resolve_default( array( 'default' => '' ) );
	}

	/**
	 * Get required field error message.
	 *
	 * @param array $context Field context.
	 * @return string Error message.
	 */
	protected function get_required_error( array $context ): string {
		return $this->get_error_message( $context['errors'], 'required', '%s is required', $context['label'] );
	}

	/**
	 * Get error message with fallback.
	 *
	 * @param array  $errors          Custom error messages.
	 * @param string $key             Error key.
	 * @param string $default_message Default message format.
	 * @param mixed  ...$args         Arguments for sprintf.
	 * @return string Error message.
	 */
	protected function get_error_message( array $errors, string $key, string $default_message, ...$args ): string {
		return $errors[ $key ] ?? sprintf( $default_message, ...$args );
	}

	/**
	 * Sanitize value based on field configuration.
	 *
	 * Applies custom sanitization callbacks or default sanitization based on field type.
	 * Supports single callable, array of callables, or type-based sanitization.
	 *
	 * @param mixed $value  Value to sanitize.
	 * @param array $config Field configuration.
	 * @return mixed Sanitized value.
	 */
	protected function sanitize_value( $value, array $config ): mixed {
		$sanitize = $config['sanitize'] ?? null;

		if ( is_callable( $sanitize ) ) {
			return $sanitize( $value );
		}

		if ( is_array( $sanitize ) ) {
			return $this->apply_sanitize_callbacks( $value, $sanitize );
		}

		$type = $config['type'] ?? Constants::DEFAULT_TYPE;

		return $this->sanitize_by_type( $value, $type );
	}

	/**
	 * Apply array of sanitization callbacks.
	 *
	 * @param mixed $value     Value to sanitize.
	 * @param array $callbacks Array of sanitization callbacks.
	 * @return mixed Sanitized value.
	 */
	protected function apply_sanitize_callbacks( $value, array $callbacks ): mixed {
		foreach ( $callbacks as $callback ) {
			if ( ! is_callable( $callback ) ) {
				continue;
			}
			$value = $callback( $value );
		}

		return $value;
	}

	/**
	 * Sanitize value by field type.
	 *
	 * @param mixed  $value Value to sanitize.
	 * @param string $type  Field type.
	 * @return mixed Sanitized value.
	 */
	protected function sanitize_by_type( $value, string $type ): mixed {
		switch ( $type ) {
			case 'number':
				return is_numeric( $value )
					? (float) $value
					: 0;

			case 'email':
				return sanitize_email( $value );

			case 'url':
				return esc_url_raw( $value );

			case 'textarea':
				return sanitize_textarea_field( $value );

			default:
				return sanitize_text_field( trim( (string) $value ) );
		}
	}

	/**
	 * Validate value against field rules.
	 *
	 * Applies validation rules including min/max, pattern, format, options, and custom validators.
	 *
	 * @param mixed $value   Sanitized value to validate.
	 * @param array $context Field context.
	 * @return string|bool True if valid, error message string if invalid.
	 */
	protected function validate_value( $value, array $context ): string|bool {
		$validation = $context['validation'];
		$errors     = $context['errors'];
		$label      = $context['label'];
		$type       = $context['type'];

		if ( isset( $validation['min'] ) ) {
			$result = $this->validate_min( $value, $validation['min'], $type, $label, $errors );
			if ( true !== $result ) {
				return $result;
			}
		}

		if ( isset( $validation['max'] ) ) {
			$result = $this->validate_max( $value, $validation['max'], $type, $label, $errors );
			if ( true !== $result ) {
				return $result;
			}
		}

		if ( isset( $validation['pattern'] ) ) {
			$result = $this->validate_pattern( $value, $validation['pattern'], $label, $errors );
			if ( true !== $result ) {
				return $result;
			}
		}

		if ( isset( $validation['format'] ) ) {
			$result = $this->validate_format( $value, $validation['format'], $label, $errors, $validation );
			if ( true !== $result ) {
				return $result;
			}
		}

		if ( 'select' === $type && ! empty( $context['options'] ) ) {
			$result = $this->validate_options( $value, $context['options'], $label, $errors );
			if ( true !== $result ) {
				return $result;
			}
		}

		if ( isset( $validation['validate'] ) && is_callable( $validation['validate'] ) ) {
			$result = $this->validate_custom( $value, $validation['validate'], $label );
			if ( true !== $result ) {
				return $result;
			}
		}

		return true;
	}

	/**
	 * Validate minimum value or length.
	 *
	 * @param mixed  $value  Value to validate.
	 * @param int    $min    Minimum value or length.
	 * @param string $type   Field type.
	 * @param string $label  Field label.
	 * @param array  $errors Custom error messages.
	 * @return string|bool True if valid, error message if invalid.
	 */
	protected function validate_min( $value, int $min, string $type, string $label, array $errors ): string|bool {
		if ( 'number' === $type ) {
			if ( $value < $min ) {
				return $this->get_error_message( $errors, 'min', '%s must be at least %d', $label, $min );
			}
		} elseif ( strlen( (string) $value ) < $min ) {
			return $this->get_error_message( $errors, 'min', '%s must be at least %d characters', $label, $min );
		}

		return true;
	}

	/**
	 * Validate maximum value or length.
	 *
	 * @param mixed  $value  Value to validate.
	 * @param int    $max    Maximum value or length.
	 * @param string $type   Field type.
	 * @param string $label  Field label.
	 * @param array  $errors Custom error messages.
	 * @return string|bool True if valid, error message if invalid.
	 */
	protected function validate_max( $value, int $max, string $type, string $label, array $errors ): string|bool {
		if ( 'number' === $type ) {
			if ( $value > $max ) {
				return $this->get_error_message( $errors, 'max', '%s must be at most %d', $label, $max );
			}
		} elseif ( strlen( (string) $value ) > $max ) {
			return $this->get_error_message( $errors, 'max', '%s must be at most %d characters', $label, $max );
		}

		return true;
	}

	/**
	 * Validate pattern match.
	 *
	 * @param mixed  $value   Value to validate.
	 * @param string $pattern Regular expression pattern.
	 * @param string $label   Field label.
	 * @param array  $errors  Custom error messages.
	 * @return string|bool True if valid, error message if invalid.
	 */
	protected function validate_pattern( $value, string $pattern, string $label, array $errors ): string|bool {
		$result = @preg_match( $pattern, (string) $value );

		if ( false === $result ) {
			return sprintf( '%s has invalid validation pattern', $label );
		}

		if ( ! $result ) {
			return $this->get_error_message( $errors, 'pattern', '%s format is invalid', $label );
		}

		return true;
	}

	/**
	 * Validate select field options.
	 *
	 * @param mixed  $value   Value to validate.
	 * @param array  $options Available options.
	 * @param string $label   Field label.
	 * @param array  $errors  Custom error messages.
	 * @return string|bool True if valid, error message if invalid.
	 */
	protected function validate_options( $value, array $options, string $label, array $errors ): string|bool {
		if ( ! array_key_exists( $value, $options ) ) {
			return $this->get_error_message( $errors, 'options', '%s must be one of the available options', $label );
		}

		return true;
	}

	/**
	 * Validate with custom callback.
	 *
	 * @param mixed    $value    Value to validate.
	 * @param callable $callback Validation callback.
	 * @param string   $label    Field label.
	 * @return string|bool True if valid, error message if invalid.
	 */
	protected function validate_custom( $value, callable $callback, string $label ): string|bool {
		$result = $callback( $value );

		if ( true !== $result ) {
			return is_string( $result ) ? $result : sprintf( '%s is invalid', $label );
		}

		return true;
	}

	/**
	 * Validate format.
	 *
	 * Validates value against specific format types (email, url, date).
	 *
	 * @param mixed  $value      Value to validate.
	 * @param string $format     Format type.
	 * @param string $label      Field label.
	 * @param array  $errors     Custom error messages.
	 * @param array  $validation Validation rules array.
	 * @return string|bool True if valid, error message if invalid.
	 */
	protected function validate_format( $value, string $format, string $label, array $errors, array $validation = array() ): string|bool {
		if ( '' === $value ) {
			return true;
		}

		$date_format = $validation['date_format'] ?? 'Y-m-d';

		return match ( $format ) {
			'email' => $this->validate_email_format( $value, $label, $errors ),
			'url' => $this->validate_url_format( $value, $label, $errors ),
			'date' => $this->validate_date_format( $value, $label, $errors, $date_format ),
			default => true,
		};
	}

	/**
	 * Validate email format.
	 *
	 * @param mixed  $value  Value to validate.
	 * @param string $label  Field label.
	 * @param array  $errors Custom error messages.
	 * @return string|bool True if valid, error message if invalid.
	 */
	protected function validate_email_format( $value, string $label, array $errors ): string|bool {
		if ( ! is_email( $value ) ) {
			return $this->get_error_message( $errors, 'format', '%s must be a valid email address', $label );
		}

		return true;
	}

	/**
	 * Validate URL format.
	 *
	 * @param mixed  $value  Value to validate.
	 * @param string $label  Field label.
	 * @param array  $errors Custom error messages.
	 * @return string|bool True if valid, error message if invalid.
	 */
	protected function validate_url_format( $value, string $label, array $errors ): string|bool {
		if ( false === wp_http_validate_url( $value ) ) {
			return $this->get_error_message( $errors, 'format', '%s must be a valid URL', $label );
		}

		return true;
	}

	/**
	 * Validate date format.
	 *
	 * @param mixed  $value   Value to validate.
	 * @param string $label   Field label.
	 * @param array  $errors  Custom error messages.
	 * @param string $format  Date format (default: Y-m-d).
	 * @return string|bool True if valid, error message if invalid.
	 */
	protected function validate_date_format( $value, string $label, array $errors, string $format = 'Y-m-d' ): string|bool {
		$date = \DateTime::createFromFormat( $format, $value );

		if ( false === $date || $date->format( $format ) !== $value ) {
			return $this->get_error_message( $errors, 'format', '%s must be a valid date in ' . $format . ' format', $label );
		}

		return true;
	}



	/**
	 * Resolve default value.
	 *
	 * Handles callable defaults and static values.
	 * Uses Value_Resolver trait for consistent behavior.
	 *
	 * @param array $config Field configuration.
	 * @return mixed Default value.
	 */
	protected function resolve_default( array $config ): mixed {
		$default = $config['default'] ?? '';
		return $this->resolve_callable( $default );
	}
}
