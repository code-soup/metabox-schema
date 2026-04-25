<?php
/**
 * Debug Helper Trait
 *
 * Provides centralized debug output methods that respect WP_DEBUG setting.
 *
 * @package    CodeSoup\MetaboxSchema
 * @subpackage Utilities
 */

namespace CodeSoup\MetaboxSchema;

/**
 * Debug Helper Trait.
 *
 * Centralized methods for debug output during development.
 */
trait Debug_Helper {

	/**
	 * Trigger error message if WP_DEBUG is enabled.
	 *
	 * Only triggers error when WP_DEBUG is true. Safe to call in production.
	 *
	 * @param string $message Error message.
	 * @param int    $level   Error level (E_USER_NOTICE, E_USER_WARNING, E_USER_ERROR).
	 */
	protected function maybe_trigger_error( string $message, int $level = E_USER_WARNING ): void {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error -- Intentional debug output for developers.
			trigger_error(
				$message, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Debug message for developers, not user output.
				$level
			);
		}
	}

	/**
	 * Output debug message as HTML comment if WP_DEBUG is enabled.
	 *
	 * Only outputs when WP_DEBUG is true. Safe to call in production.
	 *
	 * @param string $message Debug message to output.
	 */
	protected function maybe_output_debug_comment( string $message ): void {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			printf(
				'<!-- %s -->',
				esc_html( $message )
			);
		}
	}
}
