<?php
namespace ModernMedia\WPLib\Widget;

class Search extends BaseWidget {

	/**
	 * @param $instance
	 * @param $reason_not_displayed
	 * @return bool
	 */
	protected function is_widget_displayed($instance, &$reason_not_displayed) {
		return true;
	}

	/**
	 * @return string
	 */
	protected function get_name() {
		return 'MM Search';
	}

	/**
	 * @return string
	 */
	protected function get_desc() {
		return 'A slightly better search widget';
	}

	/**
	 * @return array
	 */
	protected function get_instance_defaults() {
		return array(
			'display_title' => true,
			'title' => 'Search',

		);
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
		ob_start();
		get_search_form(true);
		return ob_get_clean();
	}

	/**
	 * @param $instance
	 * @return void
	 */
	protected function print_form_fields($instance) {}

	/**
	 * @param $instance
	 * @return void
	 */
	protected function validate(&$instance) {}
}


