<?php
/**
 * Config_Sanitizer Class
 *
 * Handles sanitization of field configuration arrays.
 * Provides centralized sanitization logic to ensure consistent data handling.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema;

/**
 * Config_Sanitizer Class.
 *
 * Sanitizes field configuration data.
 */
class Config_Sanitizer {

	/**
	 * Sanitization rules mapping.
	 *
	 * Maps configuration keys to their sanitization callbacks.
	 *
	 * @var array<string, string>
	 */
	private const SANITIZATION_RULES = array(
		'name'        => 'sanitize_key',
		'type'        => 'sanitize_key',
		'label'       => 'sanitize_text_field',
		'form_prefix' => 'sanitize_key',
		'rows'        => 'absint',
	);

	/**
	 * Sanitize field configuration.
	 *
	 * Applies sanitization rules to configuration array.
	 *
	 * @param array $config Raw configuration array.
	 * @return array Sanitized configuration.
	 */
	public function sanitize( array $config ): array {
		$sanitized = $config;

		foreach ( self::SANITIZATION_RULES as $key => $callback ) {
			if ( isset( $sanitized[ $key ] ) && is_callable( $callback ) ) {
				$sanitized[ $key ] = call_user_func( $callback, $sanitized[ $key ] );
			}
		}

		$sanitized = $this->sanitize_wrapper( $sanitized );
		$sanitized = $this->sanitize_options( $sanitized );
		$sanitized = $this->sanitize_attributes( $sanitized );

		return $sanitized;
	}

	/**
	 * Sanitize wrapper tag.
	 *
	 * Validates wrapper against whitelist, falls back to default if invalid.
	 *
	 * @param array $config Configuration array.
	 * @return array Configuration with sanitized wrapper.
	 */
	protected function sanitize_wrapper( array $config ): array {
		if ( isset( $config['wrapper'] ) && ! in_array( $config['wrapper'], Constants::VALID_WRAPPER_TAGS, true ) ) {
			$config['wrapper'] = Constants::DEFAULT_WRAPPER;
		}
		return $config;
	}

	/**
	 * Sanitize options array.
	 *
	 * Sanitizes all option labels for select fields.
	 *
	 * @param array $config Configuration array.
	 * @return array Configuration with sanitized options.
	 */
	protected function sanitize_options( array $config ): array {
		if ( isset( $config['options'] ) && is_array( $config['options'] ) ) {
			$sanitized_options = array();
			foreach ( $config['options'] as $key => $value ) {
				$sanitized_options[ $key ] = sanitize_text_field( $value );
			}
			$config['options'] = $sanitized_options;
		}
		return $config;
	}

	/**
	 * Sanitize attributes array.
	 *
	 * Validates attribute structure and filters non-scalar values.
	 * Note: Attribute values are NOT escaped here - they will be escaped at output time.
	 *
	 * @param array $config Configuration array.
	 * @return array Configuration with validated attributes.
	 */
	protected function sanitize_attributes( array $config ): array {
		if ( isset( $config['attributes'] ) && is_array( $config['attributes'] ) ) {
			$sanitized_attrs = array();
			foreach ( $config['attributes'] as $key => $value ) {
				if ( is_scalar( $value ) ) {
					$sanitized_attrs[ sanitize_key( $key ) ] = $value;
				}
			}
			$config['attributes'] = $sanitized_attrs;
		}
		return $config;
	}
}
