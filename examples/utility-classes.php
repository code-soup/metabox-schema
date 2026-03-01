<?php
/**
 * Utility Classes Example
 *
 * Demonstrates how to use utility classes directly:
 * - Constants - Package constants
 * - Config_Sanitizer - Configuration sanitization
 * - String_Formatter - String formatting utilities
 *
 * @package CodeSoup\MetaboxSchema
 */

require_once __DIR__ . '/../vendor/autoload.php';

use CodeSoup\MetaboxSchema\Constants;
use CodeSoup\MetaboxSchema\Config_Sanitizer;
use CodeSoup\MetaboxSchema\String_Formatter;

echo '<!DOCTYPE html>';
echo '<html>';
echo '<head>';
echo '<title>Utility Classes Example</title>';
echo '<style>
body { font-family: Arial, sans-serif; max-width: 1200px; margin: 20px auto; padding: 20px; }
h1 { color: #333; }
h2 { color: #666; margin-top: 30px; }
pre { background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto; }
.section { margin-bottom: 40px; }
</style>';
echo '</head>';
echo '<body>';

echo '<h1>Utility Classes Example</h1>';

echo '<div class="section">';
echo '<h2>1. Constants Class</h2>';
echo '<p>Access package constants for consistent configuration:</p>';

echo '<h3>Default Values:</h3>';
echo '<pre>';
echo 'DEFAULT_TYPE: ' . Constants::DEFAULT_TYPE . "\n";
echo 'DEFAULT_WRAPPER: ' . Constants::DEFAULT_WRAPPER . "\n";
echo 'DEFAULT_HEADING_TAG: ' . Constants::DEFAULT_HEADING_TAG . "\n";
echo 'DEFAULT_ROWS: ' . Constants::DEFAULT_ROWS . "\n";
echo 'FORM_PREFIX_DELIMITER: ' . Constants::FORM_PREFIX_DELIMITER . "\n";
echo '</pre>';

echo '<h3>Valid Wrapper Tags:</h3>';
echo '<pre>';
print_r( Constants::VALID_WRAPPER_TAGS );
echo '</pre>';

echo '<h3>Special Types:</h3>';
echo '<pre>';
print_r( Constants::SPECIAL_TYPES );
echo '</pre>';

echo '<h3>Skip Validation Types:</h3>';
echo '<pre>';
print_r( Constants::SKIP_VALIDATION_TYPES );
echo '</pre>';

echo '<h3>Usage Example:</h3>';
echo '<pre>';
$wrapper = 'div';
$is_valid = in_array( $wrapper, Constants::VALID_WRAPPER_TAGS, true );
echo "Is 'div' a valid wrapper? " . ( $is_valid ? 'Yes' : 'No' ) . "\n";

$wrapper = 'invalid_tag';
$is_valid = in_array( $wrapper, Constants::VALID_WRAPPER_TAGS, true );
echo "Is 'invalid_tag' a valid wrapper? " . ( $is_valid ? 'Yes' : 'No' ) . "\n";

$type = 'heading';
$skip = in_array( $type, Constants::SKIP_VALIDATION_TYPES, true );
echo "Should 'heading' skip validation? " . ( $skip ? 'Yes' : 'No' ) . "\n";
echo '</pre>';
echo '</div>';

echo '<div class="section">';
echo '<h2>2. String_Formatter Class</h2>';
echo '<p>Format field names and convert between naming conventions:</p>';

echo '<h3>Format Field Name (for display):</h3>';
echo '<pre>';
echo "user_email → " . String_Formatter::format_field_name( 'user_email' ) . "\n";
echo "first-name → " . String_Formatter::format_field_name( 'first-name' ) . "\n";
echo "product_SKU → " . String_Formatter::format_field_name( 'product_SKU' ) . "\n";
echo "contact_phone_number → " . String_Formatter::format_field_name( 'contact_phone_number' ) . "\n";
echo '</pre>';

echo '<h3>Convert to ID Format (dashes):</h3>';
echo '<pre>';
echo "user_email → " . String_Formatter::to_id_format( 'user_email' ) . "\n";
echo "first_name → " . String_Formatter::to_id_format( 'first_name' ) . "\n";
echo "product_sku → " . String_Formatter::to_id_format( 'product_sku' ) . "\n";
echo '</pre>';

echo '<h3>Convert to Attribute Format (underscores):</h3>';
echo '<pre>';
echo "user-email → " . String_Formatter::to_attribute_format( 'user-email' ) . "\n";
echo "first-name → " . String_Formatter::to_attribute_format( 'first-name' ) . "\n";
echo "product-sku → " . String_Formatter::to_attribute_format( 'product-sku' ) . "\n";
echo '</pre>';
echo '</div>';

echo '<div class="section">';
echo '<h2>3. Config_Sanitizer Class</h2>';
echo '<p>Sanitize field configuration arrays to prevent XSS:</p>';

$raw_config = array(
	'name' => 'user_email',
	'type' => 'email',
	'label' => '<script>alert("xss")</script>Email Address',
	'wrapper' => 'invalid_tag',
	'rows' => '10',
	'options' => array(
		'key1' => '<b>Option 1</b>',
		'key2' => 'Option 2',
	),
	'attributes' => array(
		'class' => 'form-control',
		'data-validate' => 'email',
		'onclick' => 'alert("xss")',
	),
);

echo '<h3>Raw Configuration:</h3>';
echo '<pre>';
print_r( $raw_config );
echo '</pre>';

$sanitizer = new Config_Sanitizer();
$clean_config = $sanitizer->sanitize( $raw_config );

echo '<h3>Sanitized Configuration:</h3>';
echo '<pre>';
print_r( $clean_config );
echo '</pre>';

echo '<h3>What Changed:</h3>';
echo '<ul>';
echo '<li>Label: XSS script tags removed</li>';
echo '<li>Wrapper: Invalid tag replaced with default "p"</li>';
echo '<li>Rows: String converted to integer</li>';
echo '<li>Options: HTML tags sanitized</li>';
echo '<li>Attributes: All keys and values sanitized</li>';
echo '</ul>';
echo '</div>';

echo '</body>';
echo '</html>';

