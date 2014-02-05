<?php
namespace ModernMedia\WPLib\Widget;
use ModernMedia\WPLib\Utils;
use ModernMedia\WPLib\Admin\Controls;
/**
 * @var SinglePost $this
 * @var $instance
 */
?>
<div class="mm-wp-lib-text-widget">
	<div class="mm-wp-lib-widget-form-section">
		<div class="form-field single-check">
			<?php $this->checkbox_input($instance, 'display_title', __('Display header.'));?>
		</div>
		<div class="form-field">
			<div class="label">
				<label for="<?php echo $this->get_field_id('title')?>">
					<?php _e('Header Text')?>
				</label>
			</div>
			<div class="controls">
				<?php
				$this->text_input($instance, 'title', array('class'=>'widefat title-text', 'placeholder'=>__('Header Text')));
				?>
			</div>
		</div>
		<div class="form-field">
			<div class="label">
				<label for="<?php echo $this->get_field_id('text')?>">
					<?php _e('Text')?>
				</label>
			</div>
			<div class="controls">
				<?php
				$this->text_area($instance, 'text', array('class'=>'widefat', 'rows'=>10, 'placeholder'=>__('Text or HTML')));
				?>
			</div>
		</div>
	</div>
</div>
 