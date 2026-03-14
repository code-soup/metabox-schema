<?php
/**
 * Textarea Field Class
 *
 * Handles textarea fields with configurable rows.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema\Fields;

use CodeSoup\MetaboxSchema\Abstract_Field;
use CodeSoup\MetaboxSchema\Constants;

/**
 * Textarea Field Class.
 *
 * Renders textarea fields.
 */
class Textarea_Field extends Abstract_Field {

	/**
	 * Get the template name for this field type.
	 *
	 * @return string Template name.
	 */
	protected function get_template_name(): string {
		return 'textarea';
	}
}
