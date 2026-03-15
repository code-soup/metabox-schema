<?php
/**
 * Basic Custom Field Example
 *
 * Minimal custom field implementation.
 */

use CodeSoup\MetaboxSchema\Abstract_Field;
use CodeSoup\MetaboxSchema\Field_Factory;

class Rating_Field extends Abstract_Field {

	/**
	 * Get the template name for this field type.
	 *
	 * @return string Template name.
	 */
	protected function get_template_name(): string {
		return 'rating';
	}

	/**
	 * Get maximum rating value.
	 *
	 * @return int Maximum rating.
	 */
	public function get_max_rating(): int {
		return $this->config['max_rating'] ?? 5;
	}
}

// Register the custom field type
Field_Factory::register_field_type( 'rating', Rating_Field::class );

// Use in schema
$schema = array(
	'product_rating' => array(
		'type'       => 'rating',
		'label'      => 'Product Rating',
		'max_rating' => 5,
	),
);

