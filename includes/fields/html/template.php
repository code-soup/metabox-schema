<?php
/**
 * HTML Field Template
 *
 * @package CodeSoup\MetaboxSchema
 * @var \CodeSoup\MetaboxSchema\Fields\HTML_Field $this
 */

defined( 'ABSPATH' ) || exit;

echo wp_kses( $this->get_content(), $this->get_allowed_html() );
