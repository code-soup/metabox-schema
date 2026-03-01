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
	 * @param mixed $value Value to resolve.
	 * @return mixed Resolved value.
	 */
	protected function resolve_callable( $value ): mixed {
		if ( is_callable( $value ) ) {
			return $value();
		}
		return $value;
	}

	/**
	 * Resolve entity method value.
	 *
	 * If the value is a string method name and the entity has that method,
	 * calls the method and returns the result. Otherwise returns the value as-is.
	 *
	 * @param mixed       $value  Value to resolve (should be string method name).
	 * @param object|null $entity Entity object to call method on.
	 * @return mixed Resolved value.
	 */
	protected function resolve_entity_method( $value, ?object $entity ): mixed {
		if (
			is_string( $value )
			&& ! empty( trim( $value ) )
			&& null !== $entity
			&& method_exists( $entity, $value )
		) {
			return $entity->{$value}();
		}
		return $value;
	}
}
