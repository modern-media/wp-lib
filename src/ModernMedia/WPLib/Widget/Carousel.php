<?php
namespace ModernMedia\MustUse\Widget;
use ModernMedia\MustUse\Carousel as MMCarousel;
class Carousel extends Base{

	/**
	 * @return array
	 */
	protected function get_instance_defaults() {
		return array(
			'id' => 0,
			'interval' => '5000',
			'pause' => 'hover'
		);
	}


	/**
	 * @param $instance
	 * @param $reason_not_displayed
	 * @return bool
	 */
	protected function is_widget_displayed($instance, &$reason_not_displayed) {
		return true;
	}

	/**
	 * @param $instance
	 * @return bool
	 */
	protected function is_widget_content_displayed($instance) {
		return true;
	}

	/**
	 * @param $instance
	 * @return string
	 */
	protected function get_widget_content($instance) {
		return MMCarousel::inst()->get_carousel_html($instance['id'], $instance);
	}

	/**
	 * @param $instance
	 * @return void
	 */
	protected function print_form_fields($instance) {
		$this->print_post_type_select($instance, 'id', 'Carousel', MMCarousel::PT_CAROUSEL);

		printf(
			'<p><label for="%s">Interval (ms)</label> %s</p>',
			$this->get_field_id('interval'),
			$this->text_input(
				$instance,
				'interval',
				array('size'=>'10', 'placeholder'=>'Milliseconds'),
				false
			)
		);
	}

	/**
	 * @param $instance
	 * @return void
	 */
	protected function validate(&$instance) {
		if (! is_numeric($instance['interval'])) $instance['interval'] = '5000';

	}

	/**
	 * @return string
	 */
	protected function get_name() {
		return 'Carousel Widget';
	}

	/**
	 * @return string
	 */
	protected function get_desc() {
		return 'Put a carousel in a widget.';
	}

	protected function does_widget_have_title_option(){
		return false;
	}

}