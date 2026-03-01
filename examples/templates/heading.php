<?php
/**
 * Custom Heading Template - Bootstrap Style
 *
 * This template overrides the default heading template.
 * It adds custom styling for section headings.
 *
 * AVAILABLE METHODS:
 * - $this->get_label(): Get heading text
 * - $this->get_heading_tag(): Get heading tag (h1-h6)
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

$label = $this->get_label();
$tag = $this->get_heading_tag();

if ( $label ) {
	printf(
		'<%s class="section-heading">%s</%s>',
		$tag,
		esc_html( $label ),
		$tag
	);
}

