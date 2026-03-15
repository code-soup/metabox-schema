<?php
/**
 * Value_Resolver Trait Usage Example
 */

use CodeSoup\MetaboxSchema\Value_Resolver;

class Custom_Field {
	use Value_Resolver;

	public function get_value( $config, $entity ) {
		$value = $config['value'] ?? '';

		// Resolve callable
		$value = $this->resolve_callable( $value );

		// Resolve entity method
		$value = $this->resolve_entity_method( $value, $entity );

		return $value;
	}
}

// Usage
$field  = new Custom_Field();
$entity = new class() {
	public function get_email() {
		return 'user@example.com';
	}
};

// Callable resolution
$config = array( 'value' => fn() => 'dynamic value' );
$value  = $field->get_value( $config, null );

// Entity method resolution
$config = array( 'value' => 'get_email' );
$value  = $field->get_value( $config, $entity );

