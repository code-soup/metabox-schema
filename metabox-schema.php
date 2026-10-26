<?php
/**
 * Metabox Schema
 *
 * @package           CodeSoup\MetaboxSchema
 *
 * @wordpress-plugin
 * Plugin Name:       Metabox Schema
 * Plugin URI:        https://github.com/code-soup/metabox-schema
 * Description:       Schema-driven form builder for WordPress: render fields, sanitize POST data, and validate input against a PHP schema.
 * Version:           1.3.0
 * Requires at least: 6.0
 * Requires PHP:      8.1
 * Tested up to:      7.1
 * Author:            Code Soup
 * Author URI:        https://codesoup.co
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       metabox-schema
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

/**
 * Plugin version. Keep in sync with readme.txt Stable tag and composer.json version.
 */
define( 'METABOX_SCHEMA_VERSION', '1.3.0' );

/**
 * Minimum WordPress version required (informational; matches plugin header).
 */
define( 'METABOX_SCHEMA_MIN_WP', '6.0' );

/**
 * Load Composer autoloader.
 */
$metabox_schema_autoload = __DIR__ . '/vendor/autoload.php';

if ( is_readable( $metabox_schema_autoload ) ) {
	require_once $metabox_schema_autoload;
	return;
}

/**
 * Admin notice when dependencies are missing.
 */
add_action(
	'admin_notices',
	static function (): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		printf(
			'<div class="notice notice-error"><p>%s</p></div>',
			esc_html__(
				'Metabox Schema is missing Composer dependencies. Run composer install in the plugin directory, or install the release build that includes vendor/.',
				'metabox-schema'
			)
		);
	}
);
