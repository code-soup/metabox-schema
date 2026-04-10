<?php
/**
 * Test Grid Auto-Close Functionality
 *
 * Tests that grids automatically close after the last field
 * even when 'grid' => 'end' is not specified.
 *
 * @package CodeSoup\MetaboxSchema
 */

require_once __DIR__ . '/../vendor/autoload.php';

use CodeSoup\MetaboxSchema\Renderer;

if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'sanitize_key' ) ) {
	function sanitize_key( $key ) {
		return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $key ) );
	}
}

if ( ! function_exists( 'sanitize_text_field' ) ) {
	function sanitize_text_field( $str ) {
		return strip_tags( (string) $str );
	}
}

if ( ! function_exists( 'absint' ) ) {
	function absint( $maybeint ) {
		return abs( (int) $maybeint );
	}
}

if ( ! function_exists( 'esc_attr' ) ) {
	function esc_attr( $text ) {
		return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
	}
}

echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo "<head>\n";
echo "<title>Grid Auto-Close Test</title>\n";
echo "<style>
.grid {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 1rem;
	background: #f0f0f0;
	padding: 1rem;
	margin-bottom: 2rem;
}
.grid p {
	background: white;
	padding: 0.5rem;
	margin: 0;
}
h2 {
	margin-top: 2rem;
}
</style>\n";
echo "</head>\n";
echo "<body>\n";
echo "<h1>Grid Auto-Close Test</h1>\n";

echo "<h2>Test 1: Grid with explicit end (normal behavior)</h2>\n";
echo "<p>Expected: One grid wrapper with two fields</p>\n";
echo "<form>\n";

$schema1 = array(
	'field1' => array(
		'type'  => 'text',
		'label' => 'Field 1',
		'grid'  => 'start',
	),
	'field2' => array(
		'type'  => 'text',
		'label' => 'Field 2',
		'grid'  => 'end',
	),
);

Renderer::render(
	array(
		'schema'      => $schema1,
		'form_prefix' => 'test1',
	)
);

echo "</form>\n";

echo "<h2>Test 2: Grid without explicit end (auto-close)</h2>\n";
echo "<p>Expected: One grid wrapper with two fields (grid auto-closes after last field)</p>\n";
echo "<form>\n";

$schema2 = array(
	'field1' => array(
		'type'  => 'text',
		'label' => 'Field 1',
		'grid'  => 'start',
	),
	'field2' => array(
		'type'  => 'text',
		'label' => 'Field 2',
	),
);

Renderer::render(
	array(
		'schema'      => $schema2,
		'form_prefix' => 'test2',
	)
);

echo "</form>\n";

echo "<h2>Test 3: Multiple fields in grid without explicit end</h2>\n";
echo "<p>Expected: One grid wrapper with three fields (grid auto-closes after last field)</p>\n";
echo "<form>\n";

$schema3 = array(
	'field1' => array(
		'type'  => 'text',
		'label' => 'Field 1',
		'grid'  => 'start',
	),
	'field2' => array(
		'type'  => 'text',
		'label' => 'Field 2',
	),
	'field3' => array(
		'type'  => 'text',
		'label' => 'Field 3',
	),
);

Renderer::render(
	array(
		'schema'      => $schema3,
		'form_prefix' => 'test3',
	)
);

echo "</form>\n";

echo "<h2>Test 4: Field after grid without explicit end</h2>\n";
echo "<p>Expected: Grid with two fields, then one field outside grid</p>\n";
echo "<form>\n";

$schema4 = array(
	'field1' => array(
		'type'  => 'text',
		'label' => 'Field 1 (in grid)',
		'grid'  => 'start',
	),
	'field2' => array(
		'type'  => 'text',
		'label' => 'Field 2 (in grid)',
	),
);

Renderer::render(
	array(
		'schema'      => $schema4,
		'form_prefix' => 'test4',
	)
);

echo "</form>\n";

echo "</body>\n";
echo "</html>\n";
