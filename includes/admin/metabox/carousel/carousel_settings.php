<?php
use ModernMedia\WPLib\Carousel\Carousel;

/**
 * @var $post_id
 */
$settings = Carousel::inst()->get_post_meta_settings($post_id);
?>
<p>
	<label>
		<span><?php _e('Default Interval (milliseconds)')?></span>
		<input
			class="widefat"
			type="number"
			min="0"
			step="250"
			name="<?php echo Carousel::PMK_SETTINGS?>[interval]"
			value="<?php echo $settings->interval?>"
			>
	</label>
</p>

<p>
<label>
	<span><?php _e('Default Class(es)')?></span>
	<input
		class="widefat"
		type="text"
		name="<?php echo Carousel::PMK_SETTINGS?>[class]"
		value="<?php echo $settings->class?>"
	>
</label>
</p>

<p>
	<label>
		<span><?php _e('Shortcode')?></span>
		<input
			class="widefat shortcode-input"
			type="text"
			value="<?php echo esc_attr(sprintf('[%s id="%s" /]', Carousel::SHORTCODE, $post_id))?>"
			>
	</label>
</p>
