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
	 * Track if assets already enqueued.
	 *
	 * @var bool
	 */
	private static bool $assets_enqueued = false;

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
		if ( self::$assets_enqueued ) {
			return;
		}
		self::$assets_enqueued = true;

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

		$base_url = $this->get_asset_url( __FILE__ );
		$version  = $this->get_version();

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
	 * Get asset URL based on package location.
	 *
	 * Detects whether package is in plugin, theme, or vendor directory
	 * and returns appropriate URL.
	 *
	 * @param string $file_path File path.
	 * @return string Asset URL.
	 */
	protected function get_asset_url( string $file_path ): string {
		$dir_path = dirname( $file_path );

		// Try to detect if in plugin context.
		if ( defined( 'WP_PLUGIN_DIR' ) && str_contains( $dir_path, WP_PLUGIN_DIR ) ) {
			return plugins_url( '', $file_path );
		}

		// Try to detect if in theme context.
		if ( defined( 'WP_CONTENT_DIR' ) && str_contains( $dir_path, get_template_directory() ) ) {
			$relative = str_replace( get_template_directory(), '', $dir_path );
			return get_template_directory_uri() . $relative;
		}

		// Try to detect if in child theme context.
		if ( defined( 'WP_CONTENT_DIR' ) && get_stylesheet_directory() !== get_template_directory() && str_contains( $dir_path, get_stylesheet_directory() ) ) {
			$relative = str_replace( get_stylesheet_directory(), '', $dir_path );
			return get_stylesheet_directory_uri() . $relative;
		}

		// Fallback: convert absolute path to URL via wp-content.
		if ( defined( 'WP_CONTENT_DIR' ) && str_contains( $dir_path, WP_CONTENT_DIR ) ) {
			$relative = str_replace( WP_CONTENT_DIR, '', $dir_path );
			return content_url( $relative );
		}

		// Last resort: use plugins_url.
		return plugins_url( '', $file_path );
	}

	/**
	 * Get package version.
	 *
	 * @return string Version string.
	 */
	protected function get_version(): string {
		if ( defined( 'METABOX_SCHEMA_VERSION' ) ) {
			return METABOX_SCHEMA_VERSION;
		}

		// Try to read from composer.json.
		$composer_file = dirname( __DIR__, 3 ) . '/composer.json';
		if ( file_exists( $composer_file ) ) {
			$composer_data = json_decode( file_get_contents( $composer_file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading local file, not remote URL.
			if ( isset( $composer_data['version'] ) ) {
				return $composer_data['version'];
			}
		}

		return '1.0.0';
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
