<?php
/**
 * Color Picker Field Example
 *
 * Complete custom field with multiple configuration options.
 */

use CodeSoup\MetaboxSchema\Abstract_Field;
use CodeSoup\MetaboxSchema\Field_Factory;

class Color_Picker_Field extends Abstract_Field {

	/**
	 * Get the template name for this field type.
	 *
	 * @return string Template name.
	 */
	protected function get_template_name(): string {
		return 'color-picker';
	}

	/**
	 * Get color format (hex, rgb, hsl).
	 *
	 * @return string Color format.
	 */
	public function get_color_format(): string {
		return $this->config['color_format'] ?? 'hex';
	}

	/**
	 * Get palette colors.
	 *
	 * @return array Predefined color palette.
	 */
	public function get_palette(): array {
		return $this->config['palette'] ?? array();
	}

	/**
	 * Check if alpha channel is enabled.
	 *
	 * @return bool True if alpha enabled.
	 */
	public function has_alpha(): bool {
		return $this->config['alpha'] ?? false;
	}
}

// Register the custom field type
Field_Factory::register_field_type( 'color_picker', Color_Picker_Field::class );

// Use in schema
$schema = array(
	'brand_color'  => array(
		'type'         => 'color_picker',
		'label'        => 'Brand Color',
		'color_format' => 'hex',
		'palette'      => array( '#FF0000', '#00FF00', '#0000FF' ),
		'alpha'        => true,
		'validation'   => array(
			'required' => true,
		),
	),
	'accent_color' => array(
		'type'         => 'color_picker',
		'label'        => 'Accent Color',
		'color_format' => 'rgb',
	),
);

