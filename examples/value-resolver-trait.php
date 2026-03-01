<?php
/**
 * Value_Resolver Trait Example
 *
 * Demonstrates how to use the Value_Resolver trait in your own classes
 * to handle callable and entity method value resolution.
 *
 * WHY USE THIS TRAIT:
 * - Consistent value resolution across your application
 * - Handle callable values (closures, functions)
 * - Handle entity method calls
 * - Reuse the same logic as Field and Validator classes
 *
 * @package CodeSoup\MetaboxSchema
 */

require_once __DIR__ . '/../vendor/autoload.php';

use CodeSoup\MetaboxSchema\Value_Resolver;

if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
	}
}

class User {
	private string $username;
	private string $email;

	public function __construct( string $username, string $email ) {
		$this->username = $username;
		$this->email = $email;
	}

	public function get_username(): string {
		return $this->username;
	}

	public function get_email(): string {
		return $this->email;
	}

	public function get_display_name(): string {
		return ucfirst( $this->username );
	}
}

class Custom_Processor {
	use Value_Resolver;

	public function process_value( $value, $entity = null ) {
		$value = $this->resolve_callable( $value );
		$value = $this->resolve_entity_method( $value, $entity );
		return $value;
	}

	public function process_config( array $config, $entity = null ): array {
		foreach ( $config as $key => $value ) {
			$config[ $key ] = $this->process_value( $value, $entity );
		}
		return $config;
	}
}

echo '<!DOCTYPE html>';
echo '<html>';
echo '<head>';
echo '<title>Value_Resolver Trait Example</title>';
echo '<style>
body { font-family: Arial, sans-serif; max-width: 1200px; margin: 20px auto; padding: 20px; }
h1 { color: #333; }
h2 { color: #666; margin-top: 30px; }
pre { background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto; }
.section { margin-bottom: 40px; }
.result { background: #e8f5e9; padding: 10px; border-left: 4px solid #4caf50; margin: 10px 0; }
</style>';
echo '</head>';
echo '<body>';

echo '<h1>Value_Resolver Trait Example</h1>';

$processor = new Custom_Processor();
$user = new User( 'johndoe', 'john@example.com' );

echo '<div class="section">';
echo '<h2>1. Static Values</h2>';
echo '<p>Static values are returned as-is:</p>';

$result = $processor->process_value( 'static_value' );
echo '<div class="result">';
echo '<strong>Input:</strong> "static_value"<br>';
echo '<strong>Output:</strong> ' . esc_html( $result );
echo '</div>';

$result = $processor->process_value( 12345 );
echo '<div class="result">';
echo '<strong>Input:</strong> 12345<br>';
echo '<strong>Output:</strong> ' . esc_html( (string) $result );
echo '</div>';
echo '</div>';

echo '<div class="section">';
echo '<h2>2. Callable Values (Closures)</h2>';
echo '<p>Closures are executed and their return value is used:</p>';

$result = $processor->process_value( fn() => date( 'Y-m-d' ) );
echo '<div class="result">';
echo '<strong>Input:</strong> fn() => date("Y-m-d")<br>';
echo '<strong>Output:</strong> ' . esc_html( $result );
echo '</div>';

$result = $processor->process_value( fn() => 'Generated at ' . date( 'H:i:s' ) );
echo '<div class="result">';
echo '<strong>Input:</strong> fn() => "Generated at " . date("H:i:s")<br>';
echo '<strong>Output:</strong> ' . esc_html( $result );
echo '</div>';
echo '</div>';

echo '<div class="section">';
echo '<h2>3. Callable Values (Function Names)</h2>';
echo '<p>Function names are called and their return value is used:</p>';

$result = $processor->process_value( 'time' );
echo '<div class="result">';
echo '<strong>Input:</strong> "time"<br>';
echo '<strong>Output:</strong> ' . esc_html( (string) $result ) . ' (current timestamp)';
echo '</div>';

$result = $processor->process_value( 'phpversion' );
echo '<div class="result">';
echo '<strong>Input:</strong> "phpversion"<br>';
echo '<strong>Output:</strong> ' . esc_html( $result );
echo '</div>';
echo '</div>';

echo '<div class="section">';
echo '<h2>4. Entity Method Calls</h2>';
echo '<p>String method names are called on the entity object:</p>';

$result = $processor->process_value( 'get_username', $user );
echo '<div class="result">';
echo '<strong>Input:</strong> "get_username" (with User entity)<br>';
echo '<strong>Output:</strong> ' . esc_html( $result );
echo '</div>';

$result = $processor->process_value( 'get_email', $user );
echo '<div class="result">';
echo '<strong>Input:</strong> "get_email" (with User entity)<br>';
echo '<strong>Output:</strong> ' . esc_html( $result );
echo '</div>';

$result = $processor->process_value( 'get_display_name', $user );
echo '<div class="result">';
echo '<strong>Input:</strong> "get_display_name" (with User entity)<br>';
echo '<strong>Output:</strong> ' . esc_html( $result );
echo '</div>';
echo '</div>';

echo '<div class="section">';
echo '<h2>5. Processing Configuration Arrays</h2>';
echo '<p>Process entire configuration arrays with mixed value types:</p>';

$config = array(
	'username' => 'get_username',
	'email' => 'get_email',
	'timestamp' => fn() => time(),
	'static_field' => 'Static Value',
	'php_version' => 'phpversion',
);

echo '<h3>Input Configuration:</h3>';
echo '<pre>';
print_r( $config );
echo '</pre>';

$processed = $processor->process_config( $config, $user );

echo '<h3>Processed Configuration:</h3>';
echo '<pre>';
print_r( $processed );
echo '</pre>';
echo '</div>';

echo '</body>';
echo '</html>';

