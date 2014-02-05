<?php
namespace ModernMedia\WPLib\Carousel;
use ModernMedia\WPLib\Widget\BaseWidget;
use ModernMedia\WPLib\Carousel\Data\CarouselSettingsData;
use ModernMedia\WPLib\Utils;
use ModernMedia\WPLib\Scripts;


class CarouselWidget extends BaseWidget{

	public function __construct(){
		if (is_admin()){
			global $pagenow;
			if ('widgets.php' == $pagenow ){
				$s = Scripts::inst();
				$s->enqueue(Scripts::WIDGET_GENERAL);
				$s->enqueue(Scripts::WIDGET_CAROUSEL);
			}
		}
		parent::__construct();
	}


	/**
	 * @return array
	 */
	public function get_instance_defaults() {
		return array(
			'id' => 0,
			'settings' => new CarouselSettingsData(),
			'display_title' => false,
			'title' => '',
			'title_link' => '',
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
		require Utils::get_lib_path('includes/admin/widget/carousel_form.php');
	}

	/**
	 * @param $instance
	 * @return void
	 */
	public function validate(&$instance) {
		if (! is_numeric($instance['interval'])) $instance['interval'] = '5000';
		$instance['settings'] = new CarouselSettingsData($instance['settings']);

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