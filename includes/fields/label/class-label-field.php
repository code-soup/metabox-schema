<?php
/**
 * Label Field Class
 *
 * Handles field labels.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema\Fields;

use CodeSoup\MetaboxSchema\Abstract_Field;

/**
 * Label Field Class.
 *
 * Renders field labels.
 */
class Label_Field extends Abstract_Field {

	/**
	 * Render the field.
	 */
	public function render(): void {
		$this->render_template( $this->get_template_name() );
	}

	/**
	 * Get the template name for this field type.
	 *
	 * @return string Template name.
	 */
	protected function get_template_name(): string {
		return 'label';
	}
}
