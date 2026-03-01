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

class Custom_Validator extends Validator {

	protected function sanitize_by_type( $value, string $type ): mixed {
		return match ( $type ) {
			'phone' => $this->sanitize_phone( $value ),
			'slug' => $this->sanitize_slug( $value ),
			'currency' => $this->sanitize_currency( $value ),
			default => parent::sanitize_by_type( $value, $type ),
		};
	}

	protected function validate_value( $value, array $context ): string|bool {
		$validation = $context['validation'];
		$type = $context['type'];

		if ( 'phone' === $type && isset( $validation['phone_format'] ) ) {
			$phone_error = $this->validate_phone_format(
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
			$unique_error = $this->validate_unique(
				$value,
				$context['field_name'],
				$context['label']
			);
			if ( $unique_error !== true ) {
				return $unique_error;
			}
		}

		return parent::validate_value( $value, $context );
	}

	private function sanitize_phone( $value ): string {
		return preg_replace(
			'/[^0-9+\-() ]/',
			'',
			(string) $value
		);
	}

	private function sanitize_slug( $value ): string {
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

	private function sanitize_currency( $value ): float {
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

	private function validate_phone_format(
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

	private function validate_unique(
		$value,
		string $field_name,
		string $label
	): string|bool {
		if ( $this->value_exists( $field_name, $value ) ) {
			return sprintf(
				'%s must be unique. This value already exists.',
				$label
			);
		}

		return true;
	}

	private function value_exists( string $field_name, $value ): bool {
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

if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
	}
}

echo '<h1>Custom Validator Example</h1>';

$validator = new Custom_Validator();
$validated = $validator->validate( $test_data, $schema );

echo '<h2>Input Data:</h2>';
echo '<pre>';
print_r( $test_data );
echo '</pre>';

echo '<h2>Validated Data:</h2>';
echo '<pre>';
print_r( $validated );
echo '</pre>';

if ( $validator->has_errors() ) {
	echo '<h2>Validation Errors:</h2>';
	echo '<ul>';
	foreach ( $validator->get_errors() as $field => $error ) {
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

