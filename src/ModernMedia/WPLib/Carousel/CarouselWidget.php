<?php
namespace ModernMedia\WPLib\Carousel;
use ModernMedia\WPLib\Widget\BaseWidget;

class CarouselWidget extends BaseWidget{

	/**
	 * @return array
	 */
	public function get_instance_defaults() {
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
	public function is_widget_displayed($instance, &$reason_not_displayed) {
		return true;
	}

	/**
	 * @param $instance
	 * @return bool
	 */
	public function is_widget_content_displayed($instance) {
		return true;
	}

	/**
	 * @param $args
	 * @param $instance
	 * @return string
	 */
	public function get_widget_content($args, $instance) {
		return Carousel::inst()->get_carousel_html($args['id'], $args);
	}

	/**
	 * @param $instance
	 * @return void
	 */
	public function print_form_fields($instance) {
		$this->print_post_type_select($instance, 'id', 'Carousel', Carousel::PT_CAROUSEL);

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
	public function validate(&$instance) {
		if (! is_numeric($instance['interval'])) $instance['interval'] = '5000';

	}

	/**
	 * @return string
	 */
	public function get_name() {
		return 'Carousel Widget';
	}

	/**
	 * @return string
	 */
	public function get_desc() {
		return 'Put a carousel in a widget.';
	}

	public function does_widget_have_title_option(){
		return false;
	}

}