<?php
namespace ModernMedia\WPLib\Widget;

class Copyright extends BaseWidget {

	/**
	 * @return array
	 */
	public function get_instance_defaults() {
		return array(
			'org' => 'Your Organization'
		);
	}
	public function does_widget_have_title_option(){
		return false;
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
		return sprintf(
			'Copyright &copy; %s %s. All rights reserved.',
			date('Y'),
			$args['org']
		);
	}

	/**
	 * @param $instance
	 * @return void
	 */
	public function print_form_fields($instance) {
		printf(
			'<p><label for="%s">Organization</label> %s</p>',
			$this->get_field_id('content'),
			$this->text_input(
				$instance,
				'org',
				array('class'=>'widefat', 'placeholder'=>'Your Organization', 'rows' =>10),
				false
			)
		);
	}

	/**
	 * @param $instance
	 * @return void
	 */
	public function validate(&$instance) {
		// TODO: Implement validate() method.
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return 'Copyright';
	}

	/**
	 * @return string
	 */
	public function get_desc() {
		return 'Always have an updated copyright.';
	}


}