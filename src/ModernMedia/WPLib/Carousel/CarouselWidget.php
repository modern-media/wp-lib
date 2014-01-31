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
	 * @param $reason
	 * @return bool
	 */
	public function is_widget_displayed($instance, &$reason) {
		$post = get_post($instance['id']);
		if(! $post || Carousel::PT_CAROUSEL != get_post_type($post)){
			$reason = __('No carousel selected.');
			return false;
		}
		return true;
	}


	/**
	 * @param $args
	 * @param $instance
	 * @return string
	 */
	public function get_widget_content($args, $instance) {
		return Carousel::inst()->get_carousel_html($instance['id'], $instance);
	}

	/**
	 * @param $instance
	 * @return void
	 */
	public function print_form_fields($instance) {
		$this->print_post_type_select($instance, 'id', __('Carousel'), Carousel::PT_CAROUSEL);

		printf(
			'<p><label for="%s">%s</label> %s</p>',
			$this->get_field_id('interval'),
			__('Interval (ms)'),
			$this->text_input(
				$instance,
				'interval',
				array('size'=>'10', 'placeholder'=>__('Milliseconds')),
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

}