<?php
/**
 * WP Editor Field Template
 *
 * @package CodeSoup\MetaboxSchema
 * @var \CodeSoup\MetaboxSchema\Fields\WP_Editor_Field $this
 */

defined( 'ABSPATH' ) || exit;

// wp_editor() handles escaping internally, so use raw getters.
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

