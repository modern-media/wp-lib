<?php
namespace ModernMedia\WPLib\SocialSharing\Widget;
/**
 * @var TwitterFollowWidget $this
 * @var $instance
 */
use ModernMedia\WPLib\SocialSharing\SocialSharing;

$opened = isset($instance['widget_opened_form_sections']) ? explode(',', $instance['widget_opened_form_sections']) : array();

?>
<div class="mm-wp-lib-widget-form-section">
	<div class="form-field">
		<div class="label">
			<label for="<?php echo $this->get_field_id('screen_name')?>">
				<?php _e('Twitter Screen Name')?>
			</label>
		</div>

		<div class="controls">
			<?php
			$this->text_input($instance, 'screen_name', array('class'=>'widefat', 'placeholder' => __('screen_name')));
			?>
		</div>

	</div>
	<div class="form-field single-check">
		<?php $this->checkbox_input($instance, 'show-screen-name', __('Show screen name.'));?>
	</div>
	<div class="form-field single-check">
		<?php $this->checkbox_input($instance, 'show-count', __('Show follower count.'));?>
	</div>
</div>
<div data-section="twitter-follow-advanced"
	 class="mm-wp-lib-widget-form-section toggleable<?php if(in_array('twitter-follow-advanced', $opened)) echo ' opened'?>">

	<p class="section-header">
		<a href="#"><i class="toggle-section fa fa-arrow-right<?php if(in_array('twitter-follow-advanced', $opened)) echo ' fa-rotate-90'?>"></i>
			<?php _e('Advanced Follow Button Options')?></a>
	</p>

	<div class="form-field form-field-horizontal">
		<div class="label">
			<label for="<?php echo $this->get_field_id('size')?>">
				<?php _e('Size')?>
			</label>
		</div>
		<div class="controls">
			<?php $this->select($instance, 'size', SocialSharing::inst()->get_twitter_button_size_options(), array('class' => 'widefat'))?>
		</div>
	</div>
	<div class="form-field form-field-horizontal">
		<div class="label">
			<label for="<?php echo $this->get_field_id('width')?>">
				<?php _e('Width')?>
			</label>
		</div>
		<div class="controls">
			<?php $this->text_input($instance, 'width', array('class' => 'widefat', 'placeholder' => __('px or %')))?>
		</div>

	</div>

	<div class="form-field form-field-horizontal">
		<div class="label">
			<label for="<?php echo $this->get_field_id('align')?>">
				<?php _e('Alignment')?>
			</label>
		</div>
		<div class="controls">
			<?php $this->select($instance, 'align', array('left'=>'Left', 'right'=>'Right'),array('class' => 'widefat'))?>
		</div>

	</div>

	<div class="form-field form-field-horizontal">
		<div class="label">
			<label for="<?php echo $this->get_field_id('lang')?>">
				<?php _e('Language')?>
			</label>
		</div>
		<div class="controls">
			<?php $this->text_input($instance, 'lang', array('size' => '2', 'placeholder' => 'en'))?>
		</div>

	</div>
	<div class="form-field single-check">
		<?php $this->checkbox_input($instance, 'dnt', __('Opt out of Twitter tailoring.'));?>
	</div>
</div>