<?php
/**
 * Select Field Class
 *
 * Handles select dropdown fields with options.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema\Fields;

use CodeSoup\MetaboxSchema\Abstract_Field;

/**
 * Select Field Class.
 *
 * Renders select dropdown fields.
 */
class Select_Field extends Abstract_Field {

	/**
	 * Get the template name for this field type.
	 *
	 * @return string Template name.
	 */
	protected function get_template_name(): string {
		return 'select';
	}

	/**
	 * Get select options.
	 *
	 * @return array Options array (key => label).
	 */
	public function get_options(): array {
		return $this->config['options'] ?? array();
	}

	/**
	 * Check if a value is selected.
	 *
	 * @param string $option_value Option value to check.
	 * @return bool True if selected.
	 */
	public function is_selected( string $option_value ): bool {
		return (string) $this->value === $option_value;
	}

	/**
	 * Get selected attribute string.
	 *
	 * @param string $option_value Option value to check.
	 * @return string Selected attribute or empty string.
	 */
	public function get_selected_attr( string $option_value ): string {
		return $this->is_selected( $option_value ) ? ' selected' : '';
	}
}
