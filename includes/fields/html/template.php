<?php
/**
 * HTML Field Template
 *
 * @package CodeSoup\MetaboxSchema
 * @var \CodeSoup\MetaboxSchema\Fields\HTML_Field $this
 */

defined( 'ABSPATH' ) || exit;

$allowed_html = wp_kses_allowed_html( 'post' );
echo wp_kses( $this->get_content(), $allowed_html );
