<?php
namespace ModernMedia\WPLib\Widget;
use ModernMedia\WPLib\Utils;

class TwitterFollowWidget extends BaseWidget {

	/**
	 * @return array
	 */
	public function get_instance_defaults() {

		return array(
			'screen_name' => '',
			'show-count' => false,
			'show-screen-name' => false,
			'lang' => '',
			'width' => '',
			'align' => '',
			'size' => 'medium',
			'dnt' => false
		);
	}

	public function get_id_base(){
		return 'mm-wp-lib-twitter-follow';
	}

	/**
	 * @param $instance
	 * @param $reason
	 * @return bool
	 */
	public function is_widget_displayed($instance, &$reason) {
		if (empty($instance['screen_name'])){
			$reason = __('Enter a screen name');
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
		$attrs = array(
			'href' => 'https://twitter.com/' . $instance['screen_name'],
			'class' => 'twitter-follow-button'
		);
		foreach($instance as $key => $val){
			switch($key){
				case 'show-count':
				case 'show-screen-name':
				case 'dnt':
					if ($val){
						$attrs['data-' . $key] = 'true';
					}
					break;
				case 'screen_name':
					break;
				case 'lang':
				case 'width':
				case 'align':
				case 'size':

					if (! empty($val)){
						$attrs['data-' . $key] = esc_attr($val);
					}
					break;
				default:
					break;
			}
		}
		foreach($attrs as $key => $val){
			$attrs[$key] = sprintf('%s="%s"', $key, $val);
		}
		$attrs = implode(' ', $attrs);
		return sprintf(
			'<a %s>%s</a>',
			$attrs,
			sprintf(__('Follow @%s'), $instance['screen_name'])
		);

	}

	/**
	 * @param $instance
	 * @return void
	 */
	public function print_form_fields($instance) {
		require Utils::get_lib_path('includes/admin/widget/twitter_follow_form.php');
	}

	/**
	 * @param $instance
	 * @return void
	 */
	public function validate(&$instance) {
		$instance = Utils::trim_stripslashes_deep($instance);
		$instance['screen_name'] = trim($instance['screen_name'], '@');
	}



}