<?php
/**
 * Override Single Field Template Example
 *
 * Demonstrates how to use a custom template for a specific field
 * while using default templates for all other fields.
 *
 * WHY OVERRIDE SINGLE FIELD:
 * - Add special functionality to one field
 * - Integrate third-party widgets (date pickers, WYSIWYG editors, etc.)
 * - Custom validation UI for specific fields
 * - Different styling for important fields
 *
 * HOW IT WORKS:
 * Add 'template_path' to a specific field in your schema.
 * That field will use the custom template, others use defaults.
 *
 * @package CodeSoup\MetaboxSchema
 */

require_once __DIR__ . '/../vendor/autoload.php';

use CodeSoup\MetaboxSchema\Renderer;

$schema = array(
	'title'            => array(
		'type'       => 'text',
		'label'      => 'Title',
		'validation' => array(
			'required' => true,
		),
		'value'      => 'My Article Title',
	),
	'featured_content' => array(
		'type'          => 'textarea',
		'label'         => 'Featured Content',
		'rows'          => 5,
		'template_path' => __DIR__ . '/templates/featured-textarea.php',
		'value'         => 'This is featured content with custom styling.',
		'help'          => 'This field uses a custom template with special styling',
	),
	'description'      => array(
		'type'  => 'textarea',
		'label' => 'Regular Description',
		'rows'  => 3,
		'value' => 'This is a regular description.',
		'help'  => 'This field uses the default template',
	),
	'status'           => array(
		'type'    => 'select',
		'label'   => 'Status',
		'value'   => 'draft',
		'options' => array(
			'draft'     => 'Draft',
			'published' => 'Published',
		),
	),
);

echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo "<head>\n";
echo "<title>Override Single Field Example</title>\n";
echo "<style>\n";
echo "body { font-family: Arial, sans-serif; max-width: 800px; margin: 2rem auto; padding: 0 1rem; }\n";
echo "p { margin-bottom: 1rem; }\n";
echo "label { display: block; font-weight: bold; margin-bottom: 0.25rem; }\n";
echo "input, textarea, select { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; }\n";
echo "small { display: block; margin-top: 0.25rem; color: #666; font-size: 0.875rem; }\n";
echo ".featured-field { background: #fffbea; border: 2px solid #f59e0b; padding: 1rem; border-radius: 8px; margin: 1.5rem 0; }\n";
echo ".featured-field textarea { border-color: #f59e0b; background: white; }\n";
echo ".featured-label { color: #f59e0b; font-size: 1.1rem; }\n";
echo "</style>\n";
echo "</head>\n";
echo "<body>\n";
echo "<h1>Override Single Field Template</h1>\n";
echo "<p>Notice how the 'Featured Content' field has custom styling while other fields use default templates.</p>\n";

echo "<form>\n";
Renderer::render(
	array(
		'schema'      => $schema,
		'entity'      => null,
		'form_prefix' => 'content_form',
	)
);
echo "<p><button type=\"submit\">Submit</button></p>\n";
echo "</form>\n";

echo "</body>\n";
echo "</html>\n";

