<?php
/**
 * Checkbox Group Field Class
 *
 * Handles checkbox group fields with multiple options.
 *
 * @package CodeSoup\MetaboxSchema
 */

declare( strict_types=1 );

namespace CodeSoup\MetaboxSchema\Fields;

use CodeSoup\MetaboxSchema\Abstract_Field;

defined( 'ABSPATH' ) || exit;

/**
 * Checkbox Group Field Class.
 *
 * Renders checkbox group fields with multiple selectable options.
 */
class Checkbox_Group_Field extends Abstract_Field {

	/**
	 * Get the template name for this field type.
	 *
	 * @return string Template name.
	 */
	protected function get_template_name(): string {
		return 'checkbox-group';
	}

	/**
	 * Get template file path.
	 *
	 * @param string $type Template type.
	 * @return string Template file path.
	 */
	protected function get_template_path( string $type ): string {
		// Only return custom template for checkbox-group type
		// Let parent handle label and help templates
		if ( 'checkbox-group' === $type ) {
			return __DIR__ . '/template.php';
		}

		return parent::get_template_path( $type );
	}

	/**
	 * Get checkbox options.
	 *
	 * @return array Options array (key => label).
	 */
	public function get_options(): array {
		$options = $this->config['options'] ?? array();

		if ( empty( $options ) ) {
			return array();
		}

		$sort_az        = $this->should_sort_alphabetically();
		$checked_on_top = $this->should_group_by_checked_state();

		// No sorting needed
		if ( ! $sort_az && ! $checked_on_top ) {
			return $options;
		}

		// Only alphabetical sort
		if ( $sort_az && ! $checked_on_top ) {
			return $this->apply_alphabetical_sort( $options );
		}

		// Only checked on top (no sort within groups)
		if ( ! $sort_az && $checked_on_top ) {
			return $this->apply_checked_on_top( $options );
		}

		// Both: checked on top AND sorted A-Z within each group
		return $this->apply_checked_on_top_with_sort( $options );
	}

	/**
	 * Check if options should be sorted alphabetically.
	 *
	 * @return bool
	 */
	private function should_sort_alphabetically(): bool {
		return (bool) ( $this->config['sort_az'] ?? false );
	}

	/**
	 * Check if options should be grouped by checked state.
	 *
	 * @return bool
	 */
	private function should_group_by_checked_state(): bool {
		return (bool) ( $this->config['checked_on_top'] ?? false );
	}

	/**
	 * Apply alphabetical sorting to options.
	 *
	 * @param array $options Options to sort.
	 * @return array Sorted options.
	 */
	private function apply_alphabetical_sort( array $options ): array {
		asort( $options, SORT_STRING | SORT_FLAG_CASE );
		return $options;
	}

	/**
	 * Apply checked-on-top grouping to options.
	 *
	 * @param array $options Options to group.
	 * @return array Grouped options with checked items first.
	 */
	private function apply_checked_on_top( array $options ): array {
		$checked   = array();
		$unchecked = array();

		foreach ( $options as $value => $label ) {
			if ( $this->is_checked( (string) $value ) ) {
				$checked[ $value ] = $label;
			} else {
				$unchecked[ $value ] = $label;
			}
		}

		return $checked + $unchecked;
	}

	/**
	 * Apply checked-on-top grouping with alphabetical sort within each group.
	 *
	 * @param array $options Options to group and sort.
	 * @return array Grouped and sorted options.
	 */
	private function apply_checked_on_top_with_sort( array $options ): array {
		$checked   = array();
		$unchecked = array();

		foreach ( $options as $value => $label ) {
			if ( $this->is_checked( (string) $value ) ) {
				$checked[ $value ] = $label;
			} else {
				$unchecked[ $value ] = $label;
			}
		}

		// Sort each group alphabetically
		$checked   = $this->apply_alphabetical_sort( $checked );
		$unchecked = $this->apply_alphabetical_sort( $unchecked );

		return $checked + $unchecked;
	}

	/**
	 * Get field value as array.
	 *
	 * Overrides parent to ensure value is always an array.
	 *
	 * @return array Selected values.
	 */
	public function get_value(): array {
		$value = parent::get_value();

		if ( empty( $value ) ) {
			return array();
		}

		if ( ! is_array( $value ) ) {
			return array( $value );
		}

		return $value;
	}

	/**
	 * Check if option is selected.
	 *
	 * @param string $option_value Option value to check.
	 * @return bool True if selected.
	 */
	public function is_checked( string $option_value ): bool {
		return in_array( $option_value, $this->get_value(), true );
	}
}
