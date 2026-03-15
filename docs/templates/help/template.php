<?php
/**
 * Custom Help Template - Bootstrap Style
 *
 * This template overrides the default help template.
 * It adds Bootstrap form-text class and closes the form-group div.
 *
 * AVAILABLE METHODS:
 * - $this->get_help(): Get help text
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

$help = $this->get_help();

if ( $help ) {
	printf(
		'<small class="form-text">%s</small>',
		esc_html( $help )
	);
}

echo '</div>';

