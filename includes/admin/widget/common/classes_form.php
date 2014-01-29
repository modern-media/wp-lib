<?php
namespace ModernMedia\WPLib\Widget;
/**
 * @var BaseWidget $this
 * @var $instance
 * @var $opened
 */
?>

<div data-section="classes" class="mm-wp-lib-widget-form-section toggleable<?php if(in_array('classes', $opened)) echo ' opened'?>">
	<p class="section-header">
		<a href="#"><i class="toggle-section fa fa-arrow-right<?php if(in_array('classes', $opened)) echo ' fa-rotate-90'?>"></i>
		<?php _e('Extra Container Classes/Attributes')?></a>
	</p>
	<div class="form-field">
		<div class="label">
			<label for="<?php echo $this->get_field_id('extra_classes')?>">
				<?php _e('Extra Classes')?>
			</label>
		</div>
		<div class="controls">
			<?php
			$this->text_input($instance, 'extra_classes', array('class'=>'widefat', 'placeholder'=>__('class-1 class-2')));
			?>
		</div>

	</div>

	<div class="form-field">
		<div class="label">
			<label for="<?php echo $this->get_field_id('extra_attributes')?>">
				<?php _e('Extra Attributes')?>
			</label>
		</div>
		<div class="controls">
			<?php
			$this->text_input($instance, 'extra_attributes', array('class'=>'widefat', 'placeholder'=>__('attr1="value" attr2="another"')));
			?>
			<small><?php _e('Be careful. Enclose attribute values in double quotes and make sure to escape them.')?></small>
		</div>

	</div>
</div>
