<?php
use ModernMedia\WPLib\Utils;
use ModernMedia\WPLib\Carousel\Carousel;
use ModernMedia\WPLib\Carousel\CarouselWidget;

/**
 * @var CarouselWidget $this
 * @var $instance
 */
?>
<div class="mm-wp-lib-carousel-widget-controls">



	<div class="mm-wp-lib-widget-form-section id-ctr">
		<div class="form-field">
			<div class="controls">
				<?php
				$this->print_post_type_select($instance, 'id', __('Carousel'), Carousel::PT_CAROUSEL);
				?>
			</div>
		</div>

		<?php
		$id = $this->get_field_id('settings');
		$name = $this->get_field_name('settings')
		?>

		<div class="form-field">
			<div class="label">
				<label for="<?php echo $id?>_interval">
					<?php _e('Interval (milliseconds)')?>
				</label>
			</div>
			<div class="controls">
				<input
					type="number"
					min="0"
					step="250"
					id="<?php echo $id?>_interval"
					name="<?php echo $name?>[interval]"
					value="<?php echo esc_attr($instance['settings']->interval)?>">
			</div>
		</div>

		<div class="form-field">
			<div class="label">
				<label for="<?php echo $id?>_class">
					<?php _e('Class(es)')?>
				</label>
			</div>
			<div class="controls">
				<input
					type="text"
					id="<?php echo $id?>_class"
					name="<?php echo $name?>[class]"
					value="<?php echo esc_attr($instance['settings']->class)?>">
			</div>
		</div>

	</div>

<?php
require Utils::get_lib_path('includes/admin/widget/common/title_form.php');

?>
</div>