<?php
namespace ModernMedia\WPLib\SocialSharing;
use ModernMedia\WPLib\SocialSharing\Admin\ShareThisOptionPanel;
use ModernMedia\WPLib\SocialSharing\Data\ShareThisData;
class ShareThis {

	const OK = 'mm-wp-lib-social-sharing-sharethis';

	/**
	 * @var ShareThis
	 */
	private static $instance;

	/**
	 * @return ShareThis
	 */
	public static function inst(){
		if (! self::$instance instanceof ShareThis){
			self::$instance = new ShareThis;
		}
		return self::$instance;
	}

	private function __construct(){
		if (is_admin()) {
			new ShareThisOptionPanel;
		}
		add_action('plugins_loaded', array($this, '_action_plugins_loaded'));
	}

	public function _action_plugins_loaded(){
		add_action('wp_head', array($this, '_action_wp_head'));
	}

	public function _action_wp_head(){
		$options = $this->get_options();
		if (empty($options->publisher_key)) return;
		printf(
			'
			<script type="text/javascript">var switchTo5x=true;</script>
			<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
			<script type="text/javascript">stLight.options({publisher: "%s", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
			',
			$options->publisher_key
		);
	}

	/**
	 * @return ShareThisData
	 */
	public function get_options(){
		$o = get_option(self::OK);
		if (! $o instanceof ShareThisData){
			$o = new ShareThisData;
		}
		return $o;
	}

	/**
	 * @param $arr
	 */
	public function set_options($arr){
		$o = new ShareThisData($arr);
		update_option(self::OK, $o);
	}
	
	public function get_services(){
		return array(
			'facebook' => 'Facebook',
			'twitter' => 'Tweet',
			'googleplus' => 'Google +',
			'linkedin' => 'LinkedIn',
			'sharethis' => 'ShareThis',
		);
	}


	/**
	 * @param $post
	 * @return string
	 */
	public function get_sharebar($post){
		$options = $this->get_options();
		if (empty($options->publisher_key)) return '';
		if (empty($options->share_bar_services)) return '';
		$url = get_permalink($post);
		$url = wp_get_shortlink($post);
		$avail = $this->get_services();
		$buttons = array();
		$services = explode("\n", $options->share_bar_services);
		foreach ($services as $str){
			$str = trim($str);
			if (empty($str)) continue;
			if (! array_key_exists($str, $avail)) continue;
			$buttons[] = sprintf(
				'<div class="button-ctr"><span class="st_%s_large" st_url="%s" displayText="%s"></span></div>',
				$str, $url, $avail[$str]
			);

		}
		if (! count($buttons)) return '';
		return sprintf(
			'<div class="share-bar">%s</div>',
			implode(PHP_EOL, $buttons)
		);

	}



} 