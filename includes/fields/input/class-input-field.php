<?php
/**
 * Input Field Class
 *
 * Handles all standard HTML input types:
 * text, email, url, number, date, password, tel, color, range
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema\Fields;

use CodeSoup\MetaboxSchema\Abstract_Field;

/**
 * Input Field Class.
 *
 * Renders standard HTML input fields.
 */
class Input_Field extends Abstract_Field {

	/**
	 * Get the template name for this field type.
	 *
	 * @return string Template name.
	 */
	protected function get_template_name(): string {
		return 'input';
	}
}
