<?php
/**
 * String_Formatter Class
 *
 * Provides string formatting utilities for the package.
 * Handles common string transformations like field name formatting.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema;

/**
 * String_Formatter Class.
 *
 * String formatting utilities.
 */
class String_Formatter {

	/**
	 * Format field name for display.
	 *
	 * Converts field_name or field-name to "Field name".
	 *
	 * @param string $field_name Raw field name.
	 * @return string Formatted field name.
	 */
	public static function format_field_name( string $field_name ): string {
		$formatted = str_replace( array( '_', '-' ), ' ', $field_name );
		return ucfirst( $formatted );
	}

	/**
	 * Convert field name to ID format.
	 *
	 * Converts field_name to field-name.
	 *
	 * @param string $field_name Raw field name.
	 * @return string ID-formatted field name.
	 */
	public static function to_id_format( string $field_name ): string {
		return str_replace( '_', '-', $field_name );
	}

	/**
	 * Convert field name to attribute format.
	 *
	 * Converts field-name to field_name.
	 *
	 * @param string $field_name Raw field name.
	 * @return string Attribute-formatted field name.
	 */
	public static function to_attribute_format( string $field_name ): string {
		return str_replace( '-', '_', $field_name );
	}
}
