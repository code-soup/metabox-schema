<?php
/**
 * Example: Register Custom Field Type
 *
 * Demonstrates how to create and register a custom field type.
 *
 * @package CodeSoup\MetaboxSchema
 */

require_once __DIR__ . '/../vendor/autoload.php';

use CodeSoup\MetaboxSchema\Abstract_Field;
use CodeSoup\MetaboxSchema\Renderer;

/**
 * Custom Color Picker Field
 *
 * Extends Abstract_Field to create a custom color picker with preview.
 */
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
}

// Create renderer instance and register custom field type
$renderer = new Renderer();
$renderer->register_field_type( 'color_picker', Color_Picker_Field::class );

// Use the custom field type in a schema
$schema = array(
	'brand_color'      => array(
		'type'         => 'color_picker',
		'label'        => 'Brand Color',
		'color_format' => 'hex',
		'palette'      => array( '#FF0000', '#00FF00', '#0000FF' ),
		'validation'   => array(
			'required' => true,
		),
	),
	'accent_color'     => array(
		'type'         => 'color_picker',
		'label'        => 'Accent Color',
		'color_format' => 'rgb',
	),
	'background_color' => array(
		'type'    => 'color',
		'label'   => 'Background (Standard)',
		'default' => '#FFFFFF',
	),
);

// Mock entity
$entity = new class() {
	public function get_brand_color() {
		return '#FF5733';
	}
	public function get_accent_color() {
		return 'rgb(255, 87, 51)';
	}
	public function get_background_color() {
		return '#FFFFFF';
	}
};

echo '<h2>Custom Field Type Registration Example</h2>';

echo '<h3>Custom Color Picker Fields:</h3>';
$renderer->render_fields(
	array(
		'schema'      => $schema,
		'entity'      => $entity,
		'form_prefix' => 'theme_colors',
	)
);

echo '<hr>';

echo '<h3>How It Works:</h3>';
echo '<ol>';
echo '<li>Create a class that extends <code>Abstract_Field</code></li>';
echo '<li>Implement <code>get_template_name()</code> method</li>';
echo '<li>Add custom methods for field-specific configuration</li>';
echo '<li>Create a <code>Renderer</code> instance</li>';
echo '<li>Register with <code>$renderer->register_field_type()</code></li>';
echo '<li>Use the custom type in your schema</li>';
echo '</ol>';

echo '<h3>Benefits:</h3>';
echo '<ul>';
echo '<li>Custom field types integrate seamlessly with existing fields</li>';
echo '<li>Full access to validation, sanitization, and rendering pipeline</li>';
echo '<li>Can override any Abstract_Field method for custom behavior</li>';
echo '<li>Custom types can have their own templates and assets</li>';
echo '<li><strong>Instance-based registration prevents plugin conflicts</strong></li>';
echo '<li>Each renderer has isolated field registry</li>';
echo '</ul>';

echo '<h3>Registration Validation:</h3>';
echo '<p>The registration method validates:</p>';
echo '<ul>';
echo '<li>Field type name is not empty</li>';
echo '<li>Class exists</li>';
echo '<li>Class extends Abstract_Field</li>';
echo '</ul>';

echo '<h3>Resolution Order:</h3>';
echo '<ol>';
echo '<li>Instance custom types (highest priority)</li>';
echo '<li>Built-in field types</li>';
echo '<li>Throws exception if type not found</li>';
echo '</ol>';

