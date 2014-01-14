<?php


namespace ModernMedia\WPLib\Widget;

class TitleAndTagline extends BaseWidget {

	/**
	 * @return array
	 */
	protected function get_instance_defaults() {
		return array();
	}

	protected function does_widget_have_title_option(){
		return false;
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
		$title = get_bloginfo('name');
		return sprintf(
			'<hgroup>
				<h1><a href="%s" title="%s">%s</a></h1>
				<h2>%s</h2>
			</hgroup>',
			get_bloginfo('url'),
			esc_attr($title),
			$title,
			get_bloginfo('description')

		);
	}

	/**
	 * @param $instance
	 * @return void
	 */
	protected function print_form_fields($instance) {
		// TODO: Implement print_form_fields() method.
	}

	/**
	 * @param $instance
	 * @return void
	 */
	protected function validate(&$instance) {
		// TODO: Implement validate() method.
	}

	/**
	 * @return string
	 */
	protected function get_name() {
		return 'Site Name and Tagline';
	}

	/**
	 * @return string
	 */
	protected function get_desc() {
		return 'Puts your site\'s name and tagline in a widget.';
	}
}