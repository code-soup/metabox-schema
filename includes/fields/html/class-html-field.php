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

	/**
	 * Get allowed HTML tags for wp_kses.
	 *
	 * Returns custom allowlist from config or default admin-safe tags.
	 *
	 * @return array Allowed HTML tags.
	 */
	public function get_allowed_html(): array {
		if ( isset( $this->config['allowed_html'] ) && is_array( $this->config['allowed_html'] ) ) {
			return $this->config['allowed_html'];
		}

		// Default admin-safe allowlist.
		return array(
			'p'      => array(),
			'br'     => array(),
			'strong' => array(),
			'em'     => array(),
			'b'      => array(),
			'i'      => array(),
			'a'      => array(
				'href'   => array(),
				'target' => array(),
				'rel'    => array(),
				'class'  => array(),
			),
			'ul'     => array(
				'class' => array(),
			),
			'ol'     => array(
				'class' => array(),
			),
			'li'     => array(
				'class' => array(),
			),
			'h1'     => array(
				'class' => array(),
			),
			'h2'     => array(
				'class' => array(),
			),
			'h3'     => array(
				'class' => array(),
			),
			'h4'     => array(
				'class' => array(),
			),
			'h5'     => array(
				'class' => array(),
			),
			'h6'     => array(
				'class' => array(),
			),
			'code'   => array(
				'class' => array(),
			),
			'pre'    => array(
				'class' => array(),
			),
			'div'    => array(
				'class' => array(),
				'id'    => array(),
			),
			'span'   => array(
				'class' => array(),
			),
		);
	}
}
