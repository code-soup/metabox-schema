<?php
/**
 * Value_Resolver Trait
 *
 * Provides shared value resolution logic for callable and entity method values.
 * Used by both Field and Validator classes to maintain consistent behavior.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema;

/**
 * Value_Resolver Trait.
 *
 * Handles resolution of callable values and entity methods.
 */
trait Value_Resolver {

	/**
	 * Resolve callable value.
	 *
	 * If the value is callable (closure, function name, array callable, invokable object),
	 * executes it and returns the result. Otherwise returns the value as-is.
	 *
	 * Note: Callables must not require parameters.
	 *
	 * @param mixed $value Value to resolve.
	 * @return mixed Resolved value.
	 */
	protected function resolve_callable( $value ): mixed {
		if ( is_callable( $value ) ) {
			return $this->execute_with_error_handling( $value, 'Callable resolution failed' );
		}
		return $value;
	}

	/**
	 * Resolve entity method value.
	 *
	 * If the value is a string method name and the entity has that method,
	 * calls the method and returns the result. Otherwise returns the value as-is.
	 *
	 * Note: Entity methods must not require parameters.
	 *
	 * @param mixed       $value  Value to resolve (should be string method name).
	 * @param object|null $entity Entity object to call method on.
	 * @return mixed Resolved value.
	 */
	protected function resolve_entity_method( $value, ?object $entity ): mixed {
		if (
			is_string( $value )
			&& '' !== trim( $value )
			&& null !== $entity
			&& method_exists( $entity, $value )
		) {
			return $this->execute_with_error_handling(
				fn() => $entity->{$value}(),
				'Entity method resolution failed',
				$value
			);
		}
		return $value;
	}

	/**
	 * Execute callable with error handling.
	 *
	 * @param callable $callback Callback to execute.
	 * @param string   $error_prefix Error message prefix.
	 * @param mixed    $fallback Fallback value on error.
	 * @return mixed Result of callback or fallback.
	 */
	private function execute_with_error_handling( callable $callback, string $error_prefix, $fallback = null ): mixed {
		try {
			return $callback();
		} catch ( \Throwable $e ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error -- Intentional debug warning for developers.
				trigger_error(
					sprintf(
						'%s: %s',
						$error_prefix, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Debug message for developers, not user output.
						esc_html( $e->getMessage() )
					),
					E_USER_WARNING
				);
			}
			return $fallback ?? $callback;
		}
	}
}
