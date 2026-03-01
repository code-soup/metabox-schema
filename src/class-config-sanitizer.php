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
		'heading_tag' => 'sanitize_key',
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
			if ( isset( $sanitized[ $key ] ) ) {
				$sanitized[ $key ] = $callback( $sanitized[ $key ] );
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
			$config['options'] = array_map( 'sanitize_text_field', $config['options'] );
		}
		return $config;
	}

	/**
	 * Sanitize attributes array.
	 *
	 * Sanitizes both keys and values of HTML attributes.
	 *
	 * @param array $config Configuration array.
	 * @return array Configuration with sanitized attributes.
	 */
	protected function sanitize_attributes( array $config ): array {
		if ( isset( $config['attributes'] ) && is_array( $config['attributes'] ) ) {
			$sanitized_attrs = array();
			foreach ( $config['attributes'] as $key => $value ) {
				$sanitized_attrs[ sanitize_key( $key ) ] = sanitize_text_field( (string) $value );
			}
			$config['attributes'] = $sanitized_attrs;
		}
		return $config;
	}
}
