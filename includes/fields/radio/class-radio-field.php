<?php
/**
 * Radio Field Class
 *
 * Handles radio button fields with single selection from multiple options.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema\Fields;

use CodeSoup\MetaboxSchema\Abstract_Field;

defined( 'ABSPATH' ) || exit;

/**
 * Radio Field Class.
 *
 * Renders radio button fields with single selection.
 */
class Radio_Field extends Abstract_Field {

	/**
	 * Get the template name for this field type.
	 *
	 * @return string Template name.
	 */
	protected function get_template_name(): string {
		return 'radio';
	}

	/**
	 * Get radio options.
	 *
	 * @return array Options array (key => label).
	 */
	public function get_options(): array {
		return $this->config['options'] ?? array();
	}

	/**
	 * Check if option is selected.
	 *
	 * @param string $option_value Option value to check.
	 * @return bool True if selected.
	 */
	public function is_selected( string $option_value ): bool {
		return (string) $this->get_value() === $option_value;
	}
}
