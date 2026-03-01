<?php
/**
 * Custom Label Template - Bootstrap Style
 *
 * This template overrides the default label template.
 * It adds Bootstrap classes and a required indicator.
 *
 * AVAILABLE METHODS:
 * - $this->get_label(): Get field label text
 * - $this->get_field_id(): Get field ID attribute
 * - $this->is_required(): Check if field is required
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

$label = $this->get_label();

if ( $label ) {
	printf(
		'<label for="%s" class="form-label">%s%s</label>',
		$this->get_field_id(),
		esc_html( $label ),
		$this->is_required()
			? ' <span class="required">*</span>'
			: ''
	);
}

