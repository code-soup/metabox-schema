<?php
/**
 * Custom Heading Template - Bootstrap Style
 *
 * This template overrides the default heading template.
 * It adds custom styling for section headings.
 *
 * AVAILABLE METHODS:
 * - $this->getLabel(): Get heading text
 * - $this->getHeadingTag(): Get heading tag (h1-h6)
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

$label = $this->getLabel();
$tag = $this->getHeadingTag();

if ( $label ) {
	printf(
		'<%s class="section-heading">%s</%s>',
		$tag,
		esc_html( $label ),
		$tag
	);
}

