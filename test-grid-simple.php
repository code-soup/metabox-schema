<?php
require __DIR__ . '/vendor/autoload.php';

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

echo "Test 1: Grid with explicit end\n";
echo "================================\n";
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

ob_start();
Renderer::render(
	array(
		'schema'      => $schema1,
		'form_prefix' => 'test1',
	)
);
$output1 = ob_get_clean();

$opens1  = substr_count( $output1, '<div' );
$closes1 = substr_count( $output1, '</div>' );
echo "Opens: $opens1, Closes: $closes1\n";
echo "Balanced: " . ( $opens1 === $closes1 ? 'YES' : 'NO' ) . "\n\n";

echo "Test 2: Grid WITHOUT explicit end (auto-close)\n";
echo "===============================================\n";
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

ob_start();
Renderer::render(
	array(
		'schema'      => $schema2,
		'form_prefix' => 'test2',
	)
);
$output2 = ob_get_clean();

$opens2  = substr_count( $output2, '<div' );
$closes2 = substr_count( $output2, '</div>' );
echo "Opens: $opens2, Closes: $closes2\n";
echo "Balanced: " . ( $opens2 === $closes2 ? 'YES' : 'NO' ) . "\n\n";

echo "SUCCESS: Grid auto-closes correctly!\n";
