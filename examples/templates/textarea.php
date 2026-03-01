<?php
/**
 * Custom Textarea Template - Bootstrap Style
 *
 * This template overrides the default textarea template.
 * It adds Bootstrap form-control class.
 *
 * AVAILABLE METHODS:
 * - $this->get_field_id(): Get field ID attribute
 * - $this->get_field_name(): Get field name attribute
 * - $this->get_escaped_textarea_value(): Get escaped field value
 * - $this->get_rows(): Get number of rows
 * - $this->get_required_attr(): Get required attribute string
 * - $this->get_attributes_string(): Get custom attributes as string
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

printf(
	'<textarea id="%s" name="%s" rows="%d" class="form-control"%s%s>%s</textarea>',
	$this->get_field_id(),
	$this->get_field_name(),
	$this->get_rows(),
	$this->get_required_attr(),
	$this->get_attributes_string(),
	$this->get_escaped_textarea_value()
);

