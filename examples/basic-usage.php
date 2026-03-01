<?php
/**
 * Basic Usage Example
 *
 * Demonstrates comprehensive schema definition with various field types,
 * validation rules, and error handling.
 *
 * @package CodeSoup\MetaboxSchema
 */

require_once __DIR__ . '/../vendor/autoload.php';

use CodeSoup\MetaboxSchema\Renderer;
use CodeSoup\MetaboxSchema\Validator;

$schema = array(
	'username'              => array(
		'type'       => 'text',
		'label'      => 'Username',
		'attributes' => array(
			'class'       => 'form-control',
			'placeholder' => 'Enter username',
			'data-validate' => 'true',
			'maxlength'   => 50,
		),
		'validation' => array(
			'required' => true,
			'min'      => 3,
			'max'      => 50,
			'pattern'  => '/^[a-zA-Z0-9_]+$/',
		),
		'sanitize'   => array( 'trim', 'strip_tags' ),
		'value'      => 'getUsername',
		'default'    => 'guest',
		'help'       => 'Enter your username (3-50 characters)',
		'errors'     => array(
			'required' => 'Username is required',
			'min'      => 'Username must be at least 3 characters',
		),
	),
	'email'                 => array(
		'type'       => 'email',
		'label'      => 'Email Address',
		'attributes' => array(
			'placeholder' => 'you@example.com',
		),
		'validation' => array(
			'required' => true,
			'format'   => 'email',
		),
		'sanitize'   => 'sanitize_email',
		'value'      => 'getEmail',
		'default'    => '',
		'help'       => 'Enter a valid email address',
	),
	'website'               => array(
		'type'       => 'url',
		'label'      => 'Website',
		'attributes' => array(
			'placeholder' => 'https://example.com',
		),
		'validation' => array(
			'format' => 'url',
		),
		'sanitize'   => 'esc_url_raw',
		'default'    => '',
	),
	'age'                   => array(
		'type'       => 'number',
		'label'      => 'Age',
		'attributes' => array(
			'min' => 18,
			'max' => 120,
		),
		'validation' => array(
			'required' => true,
			'min'      => 18,
			'max'      => 120,
		),
		'sanitize'   => 'absint',
		'default'    => 18,
	),
	'bio'                   => array(
		'type'       => 'textarea',
		'label'      => 'Biography',
		'rows'       => 5,
		'attributes' => array(
			'placeholder' => 'Tell us about yourself...',
		),
		'validation' => array(
			'max' => 500,
		),
		'sanitize'   => 'sanitize_textarea_field',
		'default'    => '',
		'help'       => 'Maximum 500 characters',
	),
	'country'               => array(
		'type'       => 'select',
		'label'      => 'Country',
		'validation' => array(
			'required' => true,
		),
		'default'    => 'us',
		'options'    => array(
			''   => '— Select Country —',
			'us' => 'United States',
			'uk' => 'United Kingdom',
			'ca' => 'Canada',
			'au' => 'Australia',
		),
	),
	'account_type_heading'  => array(
		'type'        => 'heading',
		'label'       => 'Account Type',
		'heading_tag' => 'h4',
	),
	'account_type'          => array(
		'type'       => 'select',
		'label'      => 'Type',
		'grid'       => 'start',
		'validation' => array(
			'required' => true,
		),
		'default'    => 'free',
		'options'    => array(
			'free'    => 'Free',
			'premium' => 'Premium',
			'enterprise' => 'Enterprise',
		),
	),
	'subscription_status'   => array(
		'type'       => 'readonly',
		'label'      => 'Status',
		'grid'       => 'end',
		'default'    => 'Active',
	),
	'registration_date'     => array(
		'type'       => 'date',
		'label'      => 'Registration Date',
		'validation' => array(
			'required' => true,
		),
		'default'    => array( 'DateHelper', 'getCurrentDate' ),
	),
	'newsletter'            => array(
		'type'       => 'text',
		'label'      => 'Newsletter Preference',
		'wrapper'    => 'div',
		'default'    => 'Yes',
		'help'       => 'Example with div wrapper instead of default p',
	),
	'no_wrapper_field'      => array(
		'type'       => 'text',
		'label'      => 'Field Without Wrapper',
		'wrapper'    => '',
		'default'    => 'No wrapper',
		'help'       => 'Example with no wrapper element',
	),
	'password'              => array(
		'type'       => 'password',
		'label'      => 'Password',
		'validation' => array(
			'required' => true,
			'min'      => 8,
		),
		'help'       => 'Example of password input type',
	),
	'phone'                 => array(
		'type'       => 'tel',
		'label'      => 'Phone Number',
		'attributes' => array(
			'placeholder' => '+1 (555) 123-4567',
		),
		'help'       => 'Example of tel input type',
	),
	'favorite_color'        => array(
		'type'    => 'color',
		'label'   => 'Favorite Color',
		'default' => '#3498db',
		'help'    => 'Example of color input type',
	),
);

class MockEntity {
	public function getUsername(): string {
		return 'john_doe';
	}

	public function getEmail(): string {
		return 'john@example.com';
	}
}

$entity = new MockEntity();

Renderer::render(
	array(
		'schema'      => $schema,
		'entity'      => $entity,
		'form_prefix' => 'user_profile',
	)
);

echo '<hr>';
echo '<h2>Validation Example</h2>';

$test_data = array(
	'username'            => 'jo',
	'email'               => 'invalid-email',
	'account_type'        => 'premium',
	'subscription_status' => 'Active',
);

$validator = new Validator();
$validated = $validator->validate( $test_data, $schema );

if ( $validator->has_errors() ) {
	echo '<h3>Validation Errors:</h3>';
	echo '<ul>';
	foreach ( $validator->get_errors() as $field => $error ) {
		printf(
			'<li><strong>%s:</strong> %s</li>',
			$field,
			$error
		);
	}
	echo '</ul>';
} else {
	echo '<p>All fields valid!</p>';
}

echo '<h3>Validated Data:</h3>';
echo '<pre>';
print_r( $validated );
echo '</pre>';

