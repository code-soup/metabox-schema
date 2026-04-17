<?php
/**
 * Field Factory Class
 *
 * Creates field instances based on field type.
 * Maps field types to their corresponding field classes.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema;

use CodeSoup\MetaboxSchema\Fields\Input_Field;
use CodeSoup\MetaboxSchema\Fields\Textarea_Field;
use CodeSoup\MetaboxSchema\Fields\Select_Field;
use CodeSoup\MetaboxSchema\Fields\Media_Field;
use CodeSoup\MetaboxSchema\Fields\WP_Editor_Field;
use CodeSoup\MetaboxSchema\Fields\HTML_Field;
use CodeSoup\MetaboxSchema\Fields\Label_Field;
use CodeSoup\MetaboxSchema\Fields\Help_Field;

/**
 * Field Factory Class.
 *
 * Factory for creating field instances.
 */
class Field_Factory {

	/**
	 * Field type to class mapping.
	 *
	 * @var array<string, string>
	 */
	private const FIELD_TYPE_MAP = array(
		'text'      => Input_Field::class,
		'email'     => Input_Field::class,
		'url'       => Input_Field::class,
		'number'    => Input_Field::class,
		'date'      => Input_Field::class,
		'password'  => Input_Field::class,
		'tel'       => Input_Field::class,
		'color'     => Input_Field::class,
		'range'     => Input_Field::class,
		'textarea'  => Textarea_Field::class,
		'select'    => Select_Field::class,
		'media'     => Media_Field::class,
		'wp_editor' => WP_Editor_Field::class,
		'html'      => HTML_Field::class,
		'label'     => Label_Field::class,
		'help'      => Help_Field::class,
	);



	/**
	 * Create a field instance.
	 *
	 * @param string      $field_name    Field name.
	 * @param array       $field_config  Field configuration.
	 * @param mixed       $entity        Entity object.
	 * @param string      $form_prefix   Form prefix.
	 * @param string|null $template_base Template base directory.
	 * @return Abstract_Field Field instance.
	 * @throws \InvalidArgumentException If field type is not supported.
	 */
	public static function create(
		string $field_name,
		array $field_config,
		mixed $entity,
		string $form_prefix,
		?string $template_base = null
	): Abstract_Field {
		$type = $field_config['type'] ?? Constants::DEFAULT_TYPE;

		if ( ! is_string( $type ) ) {
			throw new \InvalidArgumentException(
				sprintf(
					'Field type must be a string, %s given',
					gettype( $type ) // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception messages are for developers.
				)
			);
		}

		if ( isset( self::FIELD_TYPE_MAP[ $type ] ) ) {
			$class_name = self::FIELD_TYPE_MAP[ $type ];
		} else {
			throw new \InvalidArgumentException(
				sprintf(
					'Unsupported field type: %s',
					$type // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception messages are for developers, not output.
				)
			);
		}

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

		$instance = new $class_name( $config );

		// Type assertion for static analysis tools.
		assert( $instance instanceof Abstract_Field );

		return $instance;
	}

	/**
	 * Check if a field type is supported.
	 *
	 * @param string $type Field type.
	 * @return bool True if supported.
	 */
	public static function is_supported( string $type ): bool {
		return isset( self::FIELD_TYPE_MAP[ $type ] );
	}

	/**
	 * Get all supported field types.
	 *
	 * @return array<string> Supported field types.
	 */
	public static function get_supported_types(): array {
		return array_keys( self::FIELD_TYPE_MAP );
	}
}
