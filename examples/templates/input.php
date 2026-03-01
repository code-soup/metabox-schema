<?php
/**
 * Custom Input Template - Bootstrap Style
 *
 * This template overrides the default input template.
 * It adds Bootstrap form-control class and wraps in a form-group div.
 *
 * AVAILABLE METHODS:
 * - $this->get_field_id(): Get field ID attribute
 * - $this->get_field_name(): Get field name attribute
 * - $this->get_type(): Get input type
 * - $this->get_escaped_value(): Get escaped field value
 * - $this->get_required_attr(): Get required attribute string
 * - $this->get_attributes_string(): Get custom attributes as string
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

echo '<div class="form-group">';

printf(
	'<input id="%s" name="%s" type="%s" value="%s" class="form-control"%s%s />',
	$this->get_field_id(),
	$this->get_field_name(),
	$this->get_type(),
	$this->get_escaped_value(),
	$this->get_required_attr(),
	$this->get_attributes_string()
);

