<?php
/**
 * Checkbox Field Class
 *
 * Handles single checkbox fields (e.g., "I agree to terms").
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema\Fields;

use CodeSoup\MetaboxSchema\Abstract_Field;

defined( 'ABSPATH' ) || exit;

/**
 * Checkbox Field Class.
 *
 * Renders single checkbox fields.
 */
class Checkbox_Field extends Abstract_Field {

	/**
	 * Get the template name for this field type.
	 *
	 * @return string Template name.
	 */
	protected function get_template_name(): string {
		return 'checkbox';
	}
}
