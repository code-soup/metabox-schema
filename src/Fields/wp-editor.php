<?php
/**
 * WP Editor Field Template
 *
 * @package CodeSoup\MetaboxSchema
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

defined( 'ABSPATH' ) || exit;

wp_editor(
	$this->get_value(),
	$this->get_field_id(),
	array_merge(
		array(
			'textarea_name' => $this->get_field_name(),
			'textarea_rows' => $this->get_rows(),
		),
		$this->get_editor_settings()
	)
);

