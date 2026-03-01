<?php
/**
 * Featured Textarea Template
 *
 * Custom template for a special textarea field with highlighted styling.
 * This demonstrates how to create a completely custom field template.
 *
 * TEMPLATE CONTEXT:
 * Inside template files, $this refers to the Field object.
 * You have access to all Field methods.
 *
 * AVAILABLE METHODS:
 * - $this->get_field_id(): Get field ID attribute
 * - $this->get_field_name(): Get field name attribute
 * - $this->get_label(): Get field label
 * - $this->get_escaped_textarea_value(): Get escaped field value
 * - $this->get_rows(): Get number of rows
 * - $this->get_help(): Get help text
 * - $this->is_required(): Check if required
 * - $this->get_required_attr(): Get required attribute string
 * - $this->get_attributes_string(): Get custom attributes as string
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

?>
<div class="featured-field">
	<?php if ( $this->get_label() ) : ?>
		<label for="<?php echo $this->get_field_id(); ?>" class="featured-label">
			⭐ <?php echo esc_html( $this->get_label() ); ?>
			<?php if ( $this->is_required() ) : ?>
				<span style="color: red;">*</span>
			<?php endif; ?>
		</label>
	<?php endif; ?>

	<textarea
		id="<?php echo $this->get_field_id(); ?>"
		name="<?php echo $this->get_field_name(); ?>"
		rows="<?php echo $this->get_rows(); ?>"
		<?php echo $this->get_required_attr(); ?>
		<?php echo $this->get_attributes_string(); ?>
	><?php echo $this->get_escaped_textarea_value(); ?></textarea>

	<?php if ( $this->get_help() ) : ?>
		<small style="color: #92400e;">
			💡 <?php echo esc_html( $this->get_help() ); ?>
		</small>
	<?php endif; ?>
</div>

