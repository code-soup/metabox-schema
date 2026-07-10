<?php
/**
 * Radio and Checkbox Fields Example
 *
 * Demonstrates the usage of radio and checkbox field types.
 *
 * RADIO FIELDS:
 * - Single selection from multiple options
 * - Similar to select dropdown but displays all options
 * - Uses 'radio' type with 'options' array
 *
 * CHECKBOX FIELDS:
 * - Single checkbox (e.g., "I agree to terms")
 * - Uses 'checkbox' type
 * - Value is '1' when checked, empty when unchecked
 *
 * @package CodeSoup\MetaboxSchema
 */

require_once __DIR__ . '/../vendor/autoload.php';

use CodeSoup\MetaboxSchema\Renderer;
use CodeSoup\MetaboxSchema\Validator;

// Define schema with radio and checkbox fields
$schema = array(
	'gender'       => array(
		'type'       => 'radio',
		'label'      => 'Gender',
		'options'    => array(
			'male'   => 'Male',
			'female' => 'Female',
			'other'  => 'Other',
		),
		'validation' => array(
			'required' => true,
		),
		'value'      => 'male',
		'help'       => 'Select your gender',
	),
	'subscription' => array(
		'type'       => 'radio',
		'label'      => 'Subscription Plan',
		'options'    => array(
			'free'       => 'Free (Limited features)',
			'basic'      => 'Basic ($9/month)',
			'premium'    => 'Premium ($19/month)',
			'enterprise' => 'Enterprise (Contact us)',
		),
		'validation' => array(
			'required' => true,
		),
		'value'      => 'free',
	),
	'agree_terms'  => array(
		'type'       => 'checkbox',
		'label'      => 'I agree to the terms and conditions',
		'validation' => array(
			'required' => true,
		),
		'value'      => false,
		'help'       => 'You must agree to continue',
	),
	'newsletter'   => array(
		'type'  => 'checkbox',
		'label' => 'Subscribe to newsletter',
		'value' => true,
		'help'  => 'Receive weekly updates',
	),
	'email'        => array(
		'type'       => 'email',
		'label'      => 'Email Address',
		'validation' => array(
			'required' => true,
			'format'   => 'email',
		),
		'value'      => 'user@example.com',
	),
);

// Mock entity for demonstration
$entity = new class() {
	public function get_gender() {
		return 'male';
	}
	public function get_subscription() {
		return 'premium';
	}
	public function get_agree_terms() {
		return true;
	}
	public function get_newsletter() {
		return false;
	}
	public function get_email() {
		return 'john@example.com';
	}
};

echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo "<head>\n";
echo "<title>Radio and Checkbox Example</title>\n";
echo "<style>\n";
echo "body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }\n";
echo ".radio-group, .checkbox-group { margin: 10px 0; }\n";
echo ".radio-group label, .checkbox-group label { display: block; margin: 5px 0; cursor: pointer; }\n";
echo "input[type='radio'], input[type='checkbox'] { margin-right: 8px; }\n";
echo "label { font-weight: bold; display: block; margin-top: 15px; }\n";
echo "small { display: block; color: #666; margin-top: 5px; }\n";
echo ".has-required { border-left: 3px solid #e74c3c; padding-left: 10px; }\n";
echo "</style>\n";
echo "</head>\n";
echo "<body>\n";

echo '<h1>Radio and Checkbox Fields Example</h1>';

echo '<form method="post">';

// Render the form
Renderer::render(
	array(
		'schema'      => $schema,
		'entity'      => $entity,
		'form_prefix' => 'user_form',
	)
);

echo '<br><button type="submit">Submit</button>';
echo '</form>';

// Handle form submission
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['user_form'] ) ) {
	echo '<hr>';
	echo '<h2>Validation Results</h2>';

	$validator       = new Validator();
	$validated_data  = $validator->validate( $_POST['user_form'], $schema );

	if ( $validator->has_errors() ) {
		echo '<h3 style="color: red;">Validation Errors:</h3>';
		echo '<ul>';
		foreach ( $validator->get_errors() as $field => $error ) {
			echo '<li><strong>' . esc_html( $field ) . ':</strong> ' . esc_html( $error ) . '</li>';
		}
		echo '</ul>';
	} else {
		echo '<h3 style="color: green;">Form is valid!</h3>';
	}

	echo '<h3>Submitted Data:</h3>';
	echo '<pre>';
	print_r( $validated_data );
	echo '</pre>';
}

echo "</body>\n";
echo "</html>\n";
