<?php
/**
 * Constants Class
 *
 * Shared constants used across the package.
 * Provides a single source of truth for default values and configuration.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema;

/**
 * Constants Class.
 *
 * Centralized constants for the package.
 */
class Constants {

	/**
	 * Default field type.
	 */
	public const DEFAULT_TYPE = 'text';

	/**
	 * Default wrapper element.
	 */
	public const DEFAULT_WRAPPER = 'p';

	/**
	 * Default number of rows for textarea/editor.
	 */
	public const DEFAULT_ROWS = 5;

	/**
	 * Form prefix delimiter character.
	 */
	public const FORM_PREFIX_DELIMITER = '_';

	/**
	 * Field types that skip validation.
	 *
	 * @var array
	 */
	public const SKIP_VALIDATION_TYPES = array( 'html' );

	/**
	 * Valid wrapper tag names.
	 *
	 * @var array
	 */
	public const VALID_WRAPPER_TAGS = array(
		'',
		'p',
		'div',
		'span',
		'section',
		'article',
	);

	/**
	 * Reserved attribute names that should not be included in attribute string.
	 *
	 * @var array
	 */
	public const RESERVED_ATTRIBUTES = array( 'id', 'name' );

	/**
	 * Default grid wrapper class name.
	 */
	public const DEFAULT_GRID_CLASS = 'grid';

	/**
	 * Default date format for validation.
	 */
	public const DEFAULT_DATE_FORMAT = 'Y-m-d';
}
