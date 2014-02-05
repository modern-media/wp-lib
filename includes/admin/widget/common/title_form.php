<?php
namespace ModernMedia\WPLib\Widget;
/**
 * @var BaseWidget $this
 * @var $instance
 * @var $display_title_link_section
 */
if(! isset($display_title_link_section)){
	$display_title_link_section = false;
}
$opened = isset($instance['widget_opened_form_sections']) ? explode(',', $instance['widget_opened_form_sections']) : array();

?>
<div data-section="title" class="mm-wp-lib-widget-form-section toggleable<?php if(in_array('title', $opened)) echo ' opened'?>">
	<p class="section-header">
		<a href="#"><i class="toggle-section fa fa-caret-right<?php if(in_array('title', $opened)) echo ' fa-rotate-90'?>"></i>
		<?php _e('Widget Title')?></a>
	</p>
	<div class="form-field single-check">
		<?php $this->checkbox_input($instance, 'display_title', __('Display title.'));?>
	</div>
	<div class="form-field">
		<div class="label">
			<label for="<?php echo $this->get_field_id('title')?>">
				<?php _e('Title')?>
			</label>
		</div>
		<div class="controls">
			<?php
			$this->text_input($instance, 'title', array('class'=>'widefat title-text', 'placeholder'=>__('Title')));
			?>
		</div>

	</div>
	<?php
	if ($display_title_link_section) {
		?>
		<div class="form-field">
			<div class="label">
				<label for="<?php echo $this->get_field_id('title_link')?>">
					<?php _e('Title Link')?>
				</label>
			</div>
			<div class="controls">
				<?php
				$this->text_input($instance, 'title_link', array('class'=>'widefat', 'placeholder'=>__('http://')));
				?>
				<br>
				<small><?php _e('Leave blank if not linked.')?></small>
			</div>

		</div>
		<?php
	}
	?>
</div> <!-- .mm-wp-lib-widget-form-section -->

