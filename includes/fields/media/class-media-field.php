<?php
/**
 * Media Field Class
 *
 * Handles WordPress media library integration for selecting images, videos, and other media.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema\Fields;

use CodeSoup\MetaboxSchema\Abstract_Field;

/**
 * Media Field Class.
 *
 * Renders media selection fields with WordPress media library integration.
 */
class Media_Field extends Abstract_Field {

	/**
	 * Enqueue assets for media field.
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		$this->enqueue_assets();
	}

	/**
	 * Enqueue media field assets.
	 */
	protected function enqueue_assets(): void {
		if ( ! did_action( 'admin_enqueue_scripts' ) && ! doing_action( 'admin_enqueue_scripts' ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		} else {
			$this->enqueue_admin_assets();
		}
	}

	/**
	 * Enqueue admin assets.
	 */
	public function enqueue_admin_assets(): void {
		wp_enqueue_media();

		$base_url = plugins_url( '', __FILE__ );
		$version  = defined( 'METABOX_SCHEMA_VERSION' ) ? METABOX_SCHEMA_VERSION : '1.0.0';

		wp_enqueue_style(
			'metabox-schema-media-field',
			$base_url . '/css/style.css',
			array(),
			$version,
			'all'
		);

		wp_enqueue_script(
			'metabox-schema-media-field',
			$base_url . '/js/script.js',
			array( 'jquery', 'media-upload', 'media-views' ),
			$version,
			true
		);
	}

	/**
	 * Get the template name for this field type.
	 *
	 * @return string Template name.
	 */
	protected function get_template_name(): string {
		return 'media';
	}

	/**
	 * Get button text for media field.
	 *
	 * @return string Button text.
	 */
	public function get_button_text(): string {
		return $this->config['button_text'] ?? __( 'Select Media', 'metabox-schema' );
	}

	/**
	 * Get change button text for media field.
	 *
	 * @return string Change button text.
	 */
	public function get_change_button_text(): string {
		return $this->config['change_button_text'] ?? __( 'Change Media', 'metabox-schema' );
	}

	/**
	 * Get remove button text for media field.
	 *
	 * @return string Remove button text.
	 */
	public function get_remove_button_text(): string {
		return $this->config['remove_button_text'] ?? __( 'Remove', 'metabox-schema' );
	}

	/**
	 * Get media type filter for media field.
	 *
	 * @return string Media type (image, video, audio, or empty for all).
	 */
	public function get_media_type(): string {
		return $this->config['media_type'] ?? '';
	}

	/**
	 * Get media library modal title.
	 *
	 * @return string Modal title.
	 */
	public function get_media_library_title(): string {
		return $this->config['media_library_title'] ?? __( 'Select Media', 'metabox-schema' );
	}

	/**
	 * Get media library modal button text.
	 *
	 * @return string Modal button text.
	 */
	public function get_media_library_button(): string {
		return $this->config['media_library_button'] ?? __( 'Use this media', 'metabox-schema' );
	}

	/**
	 * Get preview size for media field.
	 *
	 * @return string WordPress image size.
	 */
	public function get_preview_size(): string {
		return $this->config['preview_size'] ?? 'thumbnail';
	}
}
