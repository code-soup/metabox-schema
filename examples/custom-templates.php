<?php
/**
 * Custom Templates Example
 *
 * Demonstrates how to use custom field templates instead of the default ones.
 *
 * WHY CUSTOM TEMPLATES:
 * - Add custom HTML structure around fields
 * - Integrate with CSS frameworks (Bootstrap, Tailwind, etc.)
 * - Add custom JavaScript attributes
 * - Change field appearance without modifying the package
 *
 * HOW IT WORKS:
 * 1. Create custom template files in your own directory
 * 2. Pass template_base parameter to Renderer
 * 3. Your templates are used instead of default ones
 *
 * TEMPLATE FILES AVAILABLE:
 * - input.php: For all input types (text, email, number, etc.)
 * - textarea.php: For textarea fields
 * - select.php: For select dropdowns
 * - label.php: For field labels
 * - help.php: For help text
 * - heading.php: For heading elements
 *
 * @package CodeSoup\MetaboxSchema
 */

require_once __DIR__ . '/../vendor/autoload.php';

use CodeSoup\MetaboxSchema\Renderer;

$schema = array(
	'username' => array(
		'type'       => 'text',
		'label'      => 'Username',
		'attributes' => array(
			'placeholder' => 'Enter username',
			'class'       => 'custom-input',
		),
		'validation' => array(
			'required' => true,
		),
		'help'       => 'Choose a unique username',
	),
	'email'    => array(
		'type'       => 'email',
		'label'      => 'Email Address',
		'attributes' => array(
			'placeholder' => 'you@example.com',
		),
		'validation' => array(
			'required' => true,
		),
	),
	'bio'      => array(
		'type'  => 'textarea',
		'label' => 'Biography',
		'rows'  => 5,
	),
	'country'  => array(
		'type'    => 'select',
		'label'   => 'Country',
		'options' => array(
			''   => '— Select —',
			'us' => 'United States',
			'uk' => 'United Kingdom',
			'ca' => 'Canada',
		),
	),
);

echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo "<head>\n";
echo "<title>Custom Templates Example</title>\n";
echo "<style>\n";
echo ".form-group { margin-bottom: 1.5rem; }\n";
echo ".form-label { display: block; font-weight: bold; margin-bottom: 0.5rem; }\n";
echo ".form-control { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; }\n";
echo ".form-text { display: block; margin-top: 0.25rem; font-size: 0.875rem; color: #666; }\n";
echo ".required { color: red; }\n";
echo "</style>\n";
echo "</head>\n";
echo "<body>\n";
echo "<h1>Custom Templates Example</h1>\n";

echo "<h2>Default Templates</h2>\n";
echo "<form>\n";
Renderer::render(
	array(
		'schema'      => $schema,
		'entity'      => null,
		'form_prefix' => 'default_form',
	)
);
echo "</form>\n";

echo "<hr>\n";

echo "<h2>Custom Templates (Bootstrap-style)</h2>\n";
echo "<form>\n";
Renderer::render(
	array(
		'schema'        => $schema,
		'entity'        => null,
		'form_prefix'   => 'custom_form',
		'template_base' => __DIR__ . '/templates',
	)
);
echo "</form>\n";

echo "</body>\n";
echo "</html>\n";

