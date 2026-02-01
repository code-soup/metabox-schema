<?php
/**
 * Product Metabox Schema Definition
 *
 * This file defines the schema for product metabox fields.
 * The schema is used by CodeSoup\MetaboxSchema to:
 * 1. Render form fields in the WordPress admin
 * 2. Validate submitted data
 * 3. Sanitize values before saving
 *
 * STRUCTURE:
 * Each array key is the field name (used as meta_key in database).
 * Each field configuration supports:
 * - type: Field type (text, number, email, url, textarea, select, etc.)
 * - label: Field label displayed to user
 * - attributes: HTML attributes (placeholder, step, class, etc.)
 * - validation: Validation rules (required, min, max, pattern, format)
 * - errors: Custom error messages for validation failures
 * - help: Help text displayed below field
 * - default: Default value
 * - options: Available options for select fields
 * - rows: Number of rows for textarea
 *
 * TRANSLATION:
 * All user-facing strings are wrapped in __() for translation.
 * Text domain should match your plugin/theme text domain.
 *
 * @package CodeSoup\MetaboxSchema
 */

return array(
	'product_price'       => array(
		'type'       => 'number',
		'label'      => __( 'Product Price', 'codesoup-metabox-schema' ),
		'attributes' => array(
			'step'        => '0.01',
			'placeholder' => '0.00',
		),
		'validation' => array(
			'required' => true,
			'min'      => 0,
		),
		'help'       => __( 'Enter price in USD', 'codesoup-metabox-schema' ),
	),
	'product_sku'         => array(
		'type'       => 'text',
		'label'      => __( 'SKU', 'codesoup-metabox-schema' ),
		'attributes' => array(
			'placeholder' => 'PROD-001',
		),
		'validation' => array(
			'required' => true,
			'pattern'  => '/^[A-Z0-9-]+$/',
		),
		'errors'     => array(
			'pattern' => __( 'SKU must contain only uppercase letters, numbers, and hyphens', 'codesoup-metabox-schema' ),
		),
	),
	'product_description' => array(
		'type'       => 'textarea',
		'label'      => __( 'Product Description', 'codesoup-metabox-schema' ),
		'rows'       => 5,
		'validation' => array(
			'max' => 500,
		),
		'help'       => __( 'Maximum 500 characters', 'codesoup-metabox-schema' ),
	),
	'product_status'      => array(
		'type'       => 'select',
		'label'      => __( 'Status', 'codesoup-metabox-schema' ),
		'validation' => array(
			'required' => true,
		),
		'default'    => 'in_stock',
		'options'    => array(
			'in_stock'     => __( 'In Stock', 'codesoup-metabox-schema' ),
			'out_of_stock' => __( 'Out of Stock', 'codesoup-metabox-schema' ),
			'discontinued' => __( 'Discontinued', 'codesoup-metabox-schema' ),
		),
	),
);

