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
 * - $this->getFieldId(): Get field ID attribute
 * - $this->getFieldName(): Get field name attribute
 * - $this->getLabel(): Get field label
 * - $this->getValue(): Get field value
 * - $this->getRows(): Get number of rows
 * - $this->getHelp(): Get help text
 * - $this->isRequired(): Check if required
 * - $this->getRequiredAttr(): Get required attribute string
 * - $this->getAttributesString(): Get custom attributes as string
 *
 * @var \CodeSoup\MetaboxSchema\Field $this
 */

?>
<div class="featured-field">
	<?php if ( $this->getLabel() ) : ?>
		<label for="<?php echo esc_attr( $this->getFieldId() ); ?>" class="featured-label">
			⭐ <?php echo esc_html( $this->getLabel() ); ?>
			<?php if ( $this->isRequired() ) : ?>
				<span style="color: red;">*</span>
			<?php endif; ?>
		</label>
	<?php endif; ?>
	
	<textarea 
		id="<?php echo esc_attr( $this->getFieldId() ); ?>"
		name="<?php echo esc_attr( $this->getFieldName() ); ?>"
		rows="<?php echo $this->getRows(); ?>"
		<?php echo $this->getRequiredAttr(); ?>
		<?php echo $this->getAttributesString(); ?>
	><?php echo esc_textarea( $this->getValue() ); ?></textarea>
	
	<?php if ( $this->getHelp() ) : ?>
		<small style="color: #92400e;">
			💡 <?php echo esc_html( $this->getHelp() ); ?>
		</small>
	<?php endif; ?>
</div>

