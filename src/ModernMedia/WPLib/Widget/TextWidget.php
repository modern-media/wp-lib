<?php
namespace ModernMedia\WPLib\Widget;
use ModernMedia\WPLib\Utils;

class TextWidget extends BaseWidget {
	/**
	 * @param $instance
	 * @param $reason
	 * @return bool
	 */
	public function is_widget_displayed($instance, &$reason) {
		$title = trim($instance['title']);
		$content = trim($instance['text']);
		if (empty($content) && empty($title)){
			$reason = 'Both title and content are empty.';
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
		$html = '';
		if ($instance['display_title']){
			$html = sprintf(
				'%s%s%s',
				$args['before_title'],
				$instance['title'],
				$args['after_title']
			);
		}
		$html .= $instance['text'];
		return $html;

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
		require Utils::get_lib_path('includes/admin/widget/text_form.php');
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
			'display_title' => false,
			'title' => '',
			'text' => ''
		);
	}


}


