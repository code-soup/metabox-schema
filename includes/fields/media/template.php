<?php
/**
 * Media Field Template
 *
 * @package CodeSoup\MetaboxSchema
 * @var \CodeSoup\MetaboxSchema\Fields\Media_Field $this
 */

defined( 'ABSPATH' ) || exit;

$field_id           = $this->get_field_id();
$field_name         = $this->get_field_name();
$value              = $this->get_escaped_value();
$button_text        = $this->get_button_text();
$change_button_text = $this->get_change_button_text();
$remove_button_text = $this->get_remove_button_text();

?>

<div
	class="media-field-wrapper"
	data-field-id="<?php echo esc_attr( $field_id ); ?>"
	data-has-media="<?php echo $value ? 'true' : 'false'; ?>"
	data-media-type="<?php echo esc_attr( $this->get_media_type() ); ?>"
	data-media-library-title="<?php echo esc_attr( $this->get_media_library_title() ); ?>"
	data-media-library-button="<?php echo esc_attr( $this->get_media_library_button() ); ?>"
	data-preview-size="<?php echo esc_attr( $this->get_preview_size() ); ?>"
	data-button-text="<?php echo esc_attr( $button_text ); ?>"
	data-change-button-text="<?php echo esc_attr( $change_button_text ); ?>"
>
	<input
		type="hidden"
		id="<?php echo esc_attr( $field_id ); ?>"
		name="<?php echo esc_attr( $field_name ); ?>"
		value="<?php echo esc_attr( $value ); ?>"
	/>

	<div
		class="media-preview<?php echo $value ? '' : ' hidden'; ?>"
	></div>

	<button
		type="button"
		class="button media-select-button"
	>
		<?php echo $value ? esc_html( $change_button_text ) : esc_html( $button_text ); ?>
	</button>

	<button
		type="button"
		class="button media-remove-button<?php echo $value ? '' : ' hidden'; ?>"
		title="<?php echo esc_attr( $remove_button_text ); ?>"
	>
		<span class="dashicons dashicons-no"></span>
	</button>
</div>
