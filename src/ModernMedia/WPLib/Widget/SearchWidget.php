<?php
namespace ModernMedia\WPLib\Widget;

class SearchWidget extends BaseWidget {

	/**
	 * @param $instance
	 * @param $reason
	 * @return bool
	 */
	public function is_widget_displayed($instance, &$reason) {
		return true;
	}



	/**
	 * @return array
	 */
	public function get_instance_defaults() {
		return array(
			'display_title' => true,
			'title' => 'Search',

		);
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
		ob_start();
		get_search_form(true);
		return ob_get_clean();
	}

	/**
	 * @param $instance
	 * @return void
	 */
	public function print_form_fields($instance) {}

	/**
	 * @param $instance
	 * @return void
	 */
	public function validate(&$instance) {}


}


