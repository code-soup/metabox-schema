<?php
/**
 * Extending Validator Example
 *
 * Demonstrates how to extend the Validator class to add custom validation rules.
 *
 * WHY EXTEND:
 * - Add custom validation types
 * - Override default sanitization behavior
 * - Add domain-specific validation logic
 * - Customize error messages globally
 *
 * HOW IT WORKS:
 * All validation methods are now protected, allowing you to:
 * - Override existing methods
 * - Add new validation methods
 * - Customize the validation workflow
 *
 * @package CodeSoup\MetaboxSchema
 */

require_once __DIR__ . '/../vendor/autoload.php';

use CodeSoup\MetaboxSchema\Validator;

class CustomValidator extends Validator {

	protected function sanitizeByType( $value, string $type ): mixed {
		return match ( $type ) {
			'phone' => $this->sanitizePhone( $value ),
			'slug' => $this->sanitizeSlug( $value ),
			'currency' => $this->sanitizeCurrency( $value ),
			default => parent::sanitizeByType( $value, $type ),
		};
	}

	protected function validateValue( $value, array $context ): string|bool {
		$validation = $context['validation'];
		$type = $context['type'];

		if ( 'phone' === $type && isset( $validation['phone_format'] ) ) {
			$phone_error = $this->validatePhoneFormat(
				$value,
				$validation['phone_format'],
				$context['label'],
				$context['errors']
			);
			if ( $phone_error !== true ) {
				return $phone_error;
			}
		}

		if ( isset( $validation['unique'] ) && $validation['unique'] ) {
			$unique_error = $this->validateUnique(
				$value,
				$context['field_name'],
				$context['label']
			);
			if ( $unique_error !== true ) {
				return $unique_error;
			}
		}

		return parent::validateValue( $value, $context );
	}

	private function sanitizePhone( $value ): string {
		return preg_replace(
			'/[^0-9+\-() ]/',
			'',
			(string) $value
		);
	}

	private function sanitizeSlug( $value ): string {
		$slug = strtolower( trim( (string) $value ) );
		$slug = preg_replace(
			'/[^a-z0-9-]/',
			'-',
			$slug
		);
		return preg_replace(
			'/-+/',
			'-',
			$slug
		);
	}

	private function sanitizeCurrency( $value ): float {
		$cleaned = preg_replace(
			'/[^0-9.]/',
			'',
			(string) $value
		);
		return round(
			(float) $cleaned,
			2
		);
	}

	private function validatePhoneFormat(
		$value,
		string $format,
		string $label,
		array $errors
	): string|bool {
		$patterns = array(
			'us' => '/^\+?1?\s*\(?\d{3}\)?[\s\-]?\d{3}[\s\-]?\d{4}$/',
			'international' => '/^\+?[1-9]\d{1,14}$/',
		);

		$pattern = $patterns[ $format ] ?? $patterns['international'];

		if ( ! preg_match( $pattern, $value ) ) {
			return $errors['phone_format'] ?? sprintf(
				'%s must be a valid %s phone number',
				$label,
				$format
			);
		}

		return true;
	}

	private function validateUnique(
		$value,
		string $field_name,
		string $label
	): string|bool {
		if ( $this->valueExists( $field_name, $value ) ) {
			return sprintf(
				'%s must be unique. This value already exists.',
				$label
			);
		}

		return true;
	}

	private function valueExists( string $field_name, $value ): bool {
		return false;
	}
}

$schema = array(
	'phone' => array(
		'type' => 'phone',
		'label' => 'Phone Number',
		'validation' => array(
			'required' => true,
			'phone_format' => 'us',
		),
	),
	'slug' => array(
		'type' => 'slug',
		'label' => 'URL Slug',
		'validation' => array(
			'required' => true,
			'unique' => true,
		),
	),
	'price' => array(
		'type' => 'currency',
		'label' => 'Price',
		'validation' => array(
			'required' => true,
		),
	),
);

$test_data = array(
	'phone' => '+1 (555) 123-4567',
	'slug' => 'My Product Title!',
	'price' => '$99.99',
);

echo '<h1>Custom Validator Example</h1>';

$validator = new CustomValidator();
$validated = $validator->validate(
	$test_data,
	$schema
);

echo '<h2>Input Data:</h2>';
echo '<pre>';
print_r( $test_data );
echo '</pre>';

echo '<h2>Validated Data:</h2>';
echo '<pre>';
print_r( $validated );
echo '</pre>';

if ( $validator->hasErrors() ) {
	echo '<h2>Validation Errors:</h2>';
	echo '<ul>';
	foreach ( $validator->getErrors() as $field => $error ) {
		printf(
			'<li><strong>%s:</strong> %s</li>',
			esc_html( $field ),
			esc_html( $error )
		);
	}
	echo '</ul>';
} else {
	echo '<p><strong>All fields validated successfully!</strong></p>';
}

