<?php
namespace ModernMedia\WPLib\Widget;

class Search extends BaseWidget {

	/**
	 * @param $instance
	 * @param $reason_not_displayed
	 * @return bool
	 */
	public function is_widget_displayed($instance, &$reason_not_displayed) {
		return true;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return 'MM Search';
	}

	/**
	 * @return string
	 */
	public function get_desc() {
		return 'A slightly better search widget';
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


