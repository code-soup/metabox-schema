<?php
/**
 * Constants Usage Example
 */

use CodeSoup\MetaboxSchema\Constants;

// Default values
$default_type    = Constants::DEFAULT_TYPE;           // 'text'
$default_wrapper = Constants::DEFAULT_WRAPPER;        // 'p'
$default_rows    = Constants::DEFAULT_ROWS;           // 5
$grid_class      = Constants::DEFAULT_GRID_CLASS;     // 'grid'
$date_format     = Constants::DEFAULT_DATE_FORMAT;    // 'Y-m-d'

// Arrays
$skip_validation = Constants::SKIP_VALIDATION_TYPES;  // ['html']
$valid_wrappers  = Constants::VALID_WRAPPER_TAGS;     // ['', 'p', 'div', 'span', 'section', 'article']
$reserved_attrs  = Constants::RESERVED_ATTRIBUTES;    // ['id', 'name']

// Usage example
$wrapper = 'div';
if ( in_array( $wrapper, Constants::VALID_WRAPPER_TAGS, true ) ) {
	echo 'Valid wrapper';
}

