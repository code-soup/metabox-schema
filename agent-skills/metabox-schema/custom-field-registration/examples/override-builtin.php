<?php
/**
 * Override Built-in Field Type Example
 *
 * Replace built-in field type with custom implementation.
 */

use CodeSoup\MetaboxSchema\Fields\Input_Field;
use CodeSoup\MetaboxSchema\Field_Factory;

class Enhanced_Text_Field extends Input_Field {

	/**
	 * Override to add custom attributes.
	 *
	 * @return array Field attributes.
	 */
	public function get_attributes(): array {
		$attributes = parent::get_attributes();

		// Add custom data attributes
		$attributes['data-field-name'] = $this->config['name'];

		// Add character counter
		if ( isset( $this->config['validation']['max'] ) ) {
			$attributes['data-max-length'] = $this->config['validation']['max'];
			$attributes['data-counter']    = 'true';
		}

		return $attributes;
	}

	/**
	 * Override to add custom wrapper.
	 *
	 * @param string $wrapper Wrapper tag name.
	 */
	protected function open_wrapper( string $wrapper ): void {
		if ( 'p' === $wrapper ) {
			echo '<div class="enhanced-field-wrapper">';
		} else {
			parent::open_wrapper( $wrapper );
		}
	}

	/**
	 * Override to close custom wrapper.
	 *
	 * @param string $wrapper Wrapper tag name.
	 */
	protected function close_wrapper( string $wrapper ): void {
		if ( 'p' === $wrapper ) {
			echo '</div>';
		} else {
			parent::close_wrapper( $wrapper );
		}
	}
}

// Override built-in 'text' field type
Field_Factory::register_field_type( 'text', Enhanced_Text_Field::class );

// Now all 'text' fields use Enhanced_Text_Field
$schema = array(
	'username' => array(
		'type'       => 'text',
		'label'      => 'Username',
		'validation' => array(
			'max' => 50,
		),
	),
);

