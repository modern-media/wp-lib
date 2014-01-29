<?php
namespace ModernMedia\WPLib\Widget;
class Text extends BaseWidget {
	/**
	 * @param $instance
	 * @param $reason_not_displayed
	 * @return bool
	 */
	public function is_widget_displayed($instance, &$reason_not_displayed) {
		$title = trim($instance['title']);
		$content = trim($instance['content']);
		if (empty($content) && empty($title)){
			$reason_not_displayed = 'Both title and content are empty.';
			return false;
		}
		return true;
	}


	/**
	 * @return string
	 */
	public function get_name() {
		return 'MM Text Widget';
	}

	/**
	 * @return string
	 */
	public function get_desc() {
		return 'A much better text widget.';
	}

	/**
	 * @param $instance
	 * @return bool
	 */
	public function is_widget_content_displayed($instance) {
		return true;
	}

	/**
	 * @param $instance
	 * @return string
	 */
	public function get_widget_content($instance) {
		$content = trim($instance['content']);
		return do_shortcode($content);
	}

	/**
	 * @return array
	 */
	public function get_control_options(){
		return array(
			'width' => 400
		);
	}

	/**
	 * @param $instance
	 * @return void
	 */
	public function print_form_fields($instance) {
		printf(
			'<p><label for="%s">Content</label> %s</p>',
			$this->get_field_id('content'),
			$this->text_area(
				$instance,
				'content',
				array('class'=>'widefat', 'placeholder'=>'HTML Content', 'rows' =>10),
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
	 * @return array
	 */
	public function get_instance_defaults() {
		return array(
			'content' => ''
		);
	}


}


