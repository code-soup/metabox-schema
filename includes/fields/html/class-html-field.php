<?php
/**
 * HTML Field Class
 *
 * Handles HTML content display for instructions, notices, dividers, and custom HTML.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema\Fields;

use CodeSoup\MetaboxSchema\Abstract_Field;

/**
 * HTML Field Class.
 *
 * Renders HTML content in forms.
 */
class HTML_Field extends Abstract_Field {

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
		return 'html';
	}

	/**
	 * Get HTML content.
	 *
	 * Returns raw HTML content. Content is sanitized with wp_kses() in the template.
	 * Developers should ensure content is from trusted sources.
	 *
	 * @return string HTML content (not escaped).
	 */
	public function get_content(): string {
		$content = $this->config['content'] ?? '';
		return (string) $this->resolve_callable( $content );
	}
}
