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
	 * Default heading tag.
	 */
	public const DEFAULT_HEADING_TAG = 'h6';

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
	public const SKIP_VALIDATION_TYPES = array( 'heading' );

	/**
	 * Special field types that use dedicated templates.
	 *
	 * @var array
	 */
	public const SPECIAL_TYPES = array(
		'textarea',
		'select',
		'wp_editor',
	);

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
}
