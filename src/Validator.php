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

class Validator {

	private const DEFAULT_TYPE = 'text';
	private const SKIP_VALIDATION_TYPES = array( 'heading' );

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
		$validated = array();

		foreach ( $schema as $field_name => $field_config ) {
			$type = $field_config['type'] ?? self::DEFAULT_TYPE;

			if ( in_array( $type, self::SKIP_VALIDATION_TYPES, true ) ) {
				continue;
			}

			$context = $this->buildFieldContext( $field_name, $field_config );
			$value = $data[ $field_name ] ?? null;

			if ( $this->isEmptyValue( $value ) ) {
				$validated[ $field_name ] = $this->handleEmptyValue( $field_name, $context );
				continue;
			}

			$sanitized = $this->sanitizeValue( $value, $field_config );

			if ( $context['is_required'] && $this->isEmptyValue( $sanitized ) ) {
				$this->errors[ $field_name ] = $this->getRequiredError( $context );
				$validated[ $field_name ] = $this->resolveDefault( $field_config );
				continue;
			}

			$validation_result = $this->validateValue( $sanitized, $context );

			if ( $validation_result !== true ) {
				$this->errors[ $field_name ] = $validation_result;
				$validated[ $field_name ] = $this->resolveDefault( $field_config );
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
	public function getErrors(): array {
		return $this->errors;
	}

	/**
	 * Check if validation has errors.
	 *
	 * @return bool True if errors exist, false otherwise.
	 */
	public function hasErrors(): bool {
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
	private function buildFieldContext( string $field_name, array $field_config ): array {
		$validation = $field_config['validation'] ?? array();

		return array(
			'field_name'  => $field_name,
			'label'       => $field_config['label'] ?? $this->formatFieldName( $field_name ),
			'validation'  => $validation,
			'errors'      => $field_config['errors'] ?? array(),
			'type'        => $field_config['type'] ?? self::DEFAULT_TYPE,
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
	private function isEmptyValue( $value ): bool {
		return null === $value || '' === $value;
	}

	/**
	 * Handle empty value validation.
	 *
	 * @param string $field_name Field name.
	 * @param array  $context    Field context.
	 * @return mixed Default value or empty string.
	 */
	private function handleEmptyValue( string $field_name, array $context ) {
		if ( $context['is_required'] ) {
			$this->errors[ $field_name ] = $this->getRequiredError( $context );
		}

		return $this->resolveDefault( array( 'default' => '' ) );
	}

	/**
	 * Get required field error message.
	 *
	 * @param array $context Field context.
	 * @return string Error message.
	 */
	private function getRequiredError( array $context ): string {
		return $context['errors']['required'] ?? sprintf(
			'%s is required',
			$context['label']
		);
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
	private function sanitizeValue( $value, array $config ): mixed {
		$sanitize = $config['sanitize'] ?? null;

		if ( is_callable( $sanitize ) ) {
			return $sanitize( $value );
		}

		if ( is_array( $sanitize ) ) {
			return $this->applySanitizeCallbacks( $value, $sanitize );
		}

		$type = $config['type'] ?? self::DEFAULT_TYPE;

		return $this->sanitizeByType( $value, $type );
	}

	/**
	 * Apply array of sanitization callbacks.
	 *
	 * @param mixed $value     Value to sanitize.
	 * @param array $callbacks Array of sanitization callbacks.
	 * @return mixed Sanitized value.
	 */
	private function applySanitizeCallbacks( $value, array $callbacks ): mixed {
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
	private function sanitizeByType( $value, string $type ): mixed {
		switch ( $type ) {
			case 'number':
				return is_numeric( $value )
					? (float) $value
					: 0;

			case 'email':
				return function_exists( 'sanitize_email' )
					? sanitize_email( $value )
					: filter_var( $value, FILTER_SANITIZE_EMAIL );

			case 'url':
				return function_exists( 'esc_url_raw' )
					? esc_url_raw( $value )
					: filter_var( $value, FILTER_SANITIZE_URL );

			case 'textarea':
				return function_exists( 'sanitize_textarea_field' )
					? sanitize_textarea_field( $value )
					: trim( (string) $value );

			default:
				return function_exists( 'sanitize_text_field' )
					? sanitize_text_field( trim( (string) $value ) )
					: trim( strip_tags( (string) $value ) );
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
	private function validateValue( $value, array $context ): string|bool {
		$validation = $context['validation'];
		$errors = $context['errors'];
		$label = $context['label'];
		$type = $context['type'];

		if ( isset( $validation['min'] ) ) {
			$min_error = $this->validateMin( $value, $validation['min'], $type, $label, $errors );
			if ( $min_error !== true ) {
				return $min_error;
			}
		}

		if ( isset( $validation['max'] ) ) {
			$max_error = $this->validateMax( $value, $validation['max'], $type, $label, $errors );
			if ( $max_error !== true ) {
				return $max_error;
			}
		}

		if ( isset( $validation['pattern'] ) ) {
			$pattern_error = $this->validatePattern( $value, $validation['pattern'], $label, $errors );
			if ( $pattern_error !== true ) {
				return $pattern_error;
			}
		}

		if ( isset( $validation['format'] ) ) {
			$format_error = $this->validateFormat( $value, $validation['format'], $label, $errors );
			if ( $format_error !== true ) {
				return $format_error;
			}
		}

		if ( 'select' === $type && ! empty( $context['options'] ) ) {
			$options_error = $this->validateOptions( $value, $context['options'], $label, $errors );
			if ( $options_error !== true ) {
				return $options_error;
			}
		}

		if ( isset( $validation['validate'] ) && is_callable( $validation['validate'] ) ) {
			$custom_error = $this->validateCustom( $value, $validation['validate'], $label );
			if ( $custom_error !== true ) {
				return $custom_error;
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
	private function validateMin( $value, int $min, string $type, string $label, array $errors ): string|bool {
		if ( 'number' === $type ) {
			if ( $value < $min ) {
				return $errors['min'] ?? sprintf(
					'%s must be at least %d',
					$label,
					$min
				);
			}
		} elseif ( strlen( (string) $value ) < $min ) {
			return $errors['min'] ?? sprintf(
				'%s must be at least %d characters',
				$label,
				$min
			);
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
	private function validateMax( $value, int $max, string $type, string $label, array $errors ): string|bool {
		if ( 'number' === $type ) {
			if ( $value > $max ) {
				return $errors['max'] ?? sprintf(
					'%s must be at most %d',
					$label,
					$max
				);
			}
		} elseif ( strlen( (string) $value ) > $max ) {
			return $errors['max'] ?? sprintf(
				'%s must be at most %d characters',
				$label,
				$max
			);
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
	private function validatePattern( $value, string $pattern, string $label, array $errors ): string|bool {
		$result = @preg_match( $pattern, (string) $value );

		if ( false === $result ) {
			return sprintf(
				'%s has invalid validation pattern',
				$label
			);
		}

		if ( ! $result ) {
			return $errors['pattern'] ?? sprintf(
				'%s format is invalid',
				$label
			);
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
	private function validateOptions( $value, array $options, string $label, array $errors ): string|bool {
		if ( ! array_key_exists( $value, $options ) ) {
			return $errors['options'] ?? sprintf(
				'%s must be one of the available options',
				$label
			);
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
	private function validateCustom( $value, callable $callback, string $label ): string|bool {
		$result = $callback( $value );

		if ( $result !== true ) {
			return is_string( $result )
				? $result
				: sprintf(
					'%s is invalid',
					$label
				);
		}

		return true;
	}

	/**
	 * Validate format.
	 *
	 * Validates value against specific format types (email, url, date).
	 *
	 * @param mixed  $value  Value to validate.
	 * @param string $format Format type.
	 * @param string $label  Field label.
	 * @param array  $errors Custom error messages.
	 * @return string|bool True if valid, error message if invalid.
	 */
	private function validateFormat( $value, string $format, string $label, array $errors ): string|bool {
		if ( '' === $value ) {
			return true;
		}

		return match ( $format ) {
			'email' => $this->validateEmailFormat( $value, $label, $errors ),
			'url' => $this->validateUrlFormat( $value, $label, $errors ),
			'date' => $this->validateDateFormat( $value, $label, $errors ),
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
	private function validateEmailFormat( $value, string $label, array $errors ): string|bool {
		$is_valid = function_exists( 'is_email' )
			? is_email( $value )
			: filter_var( $value, FILTER_VALIDATE_EMAIL );

		if ( ! $is_valid ) {
			return $errors['format'] ?? sprintf(
				'%s must be a valid email address',
				$label
			);
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
	private function validateUrlFormat( $value, string $label, array $errors ): string|bool {
		if ( false === filter_var( $value, FILTER_VALIDATE_URL ) ) {
			return $errors['format'] ?? sprintf(
				'%s must be a valid URL',
				$label
			);
		}

		return true;
	}

	/**
	 * Validate date format.
	 *
	 * @param mixed  $value  Value to validate.
	 * @param string $label  Field label.
	 * @param array  $errors Custom error messages.
	 * @return string|bool True if valid, error message if invalid.
	 */
	private function validateDateFormat( $value, string $label, array $errors ): string|bool {
		$timestamp = strtotime( $value );

		if ( false === $timestamp ) {
			return $errors['format'] ?? sprintf(
				'%s must be a valid date',
				$label
			);
		}

		return true;
	}

	/**
	 * Format field name for display.
	 *
	 * Converts field_name or field-name to "Field name".
	 *
	 * @param string $field_name Field name.
	 * @return string Formatted field name.
	 */
	private function formatFieldName( string $field_name ): string {
		$formatted = str_replace(
			array( '_', '-' ),
			' ',
			$field_name
		);
		return ucfirst( $formatted );
	}

	/**
	 * Resolve default value.
	 *
	 * Handles callable defaults, array-based method calls, and static values.
	 *
	 * @param array $config Field configuration.
	 * @return mixed Default value.
	 */
	private function resolveDefault( array $config ): mixed {
		$default = $config['default'] ?? '';

		if ( is_callable( $default ) ) {
			return $default();
		}

		if ( is_array( $default ) && isset( $default[0], $default[1] ) ) {
			if ( is_callable( $default ) ) {
				return $default();
			}
		}

		return $default;
	}
}

