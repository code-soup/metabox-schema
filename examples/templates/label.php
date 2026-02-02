<?php
/**
 * Custom Label Template - Bootstrap Style
 *
 * This template overrides the default label template.
 * It adds Bootstrap classes and a required indicator.
 *
 * AVAILABLE METHODS:
 * - $this->getLabel(): Get field label text
 * - $this->getFieldId(): Get field ID attribute
 * - $this->isRequired(): Check if field is required
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

$label = $this->getLabel();

if ( $label ) {
	printf(
		'<label for="%s" class="form-label">%s%s</label>',
		esc_attr( $this->getFieldId() ),
		esc_html( $label ),
		$this->isRequired()
			? ' <span class="required">*</span>'
			: ''
	);
}

