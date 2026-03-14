<?php
/**
 * WP Editor Field Class
 *
 * Handles WordPress rich text editor (TinyMCE/Gutenberg).
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema\Fields;

use CodeSoup\MetaboxSchema\Abstract_Field;
use CodeSoup\MetaboxSchema\Constants;

/**
 * WP Editor Field Class.
 *
 * Renders WordPress editor fields.
 */
class WP_Editor_Field extends Abstract_Field {

	/**
	 * Get the template name for this field type.
	 *
	 * @return string Template name.
	 */
	protected function get_template_name(): string {
		return 'wp-editor';
	}

	/**
	 * Get WP Editor settings.
	 *
	 * @return array Editor settings array.
	 */
	public function get_editor_settings(): array {
		return $this->config['editor_settings'] ?? array();
	}
}
