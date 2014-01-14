<?php
namespace ModernMedia\WPLib\Front;

use Carbon\Carbon;

class ClientTimezone {
	const CK_TZ = 'modern_media_wp_lib_front_timezone_offset';
	private static $instance = null;

	private $offset = null;


	/**
	 * @return ClientTimezone
	 */
	public static function inst(){
		if (! self::$instance instanceof ClientTimezone){
			self::$instance = new ClientTimezone;
		}
		return self::$instance;
	}

	private function __construct(){

		if (isset($_COOKIE[self::CK_TZ])){
			$this->offset = $_COOKIE[self::CK_TZ];
		}
		add_action('wp_enqueue_scripts', array($this, '_action_wp_enqueue_scripts'));
		add_action('wp_footer', array($this, '_action_wp_footer'));
	}

	/**
	 * @param Carbon $utc
	 */
	public function utc_to_local($utc){
		$local = $utc->copy();

		$off = is_null($this->offset) ? 0 : intval($this->offset);

		$local->setTimestamp($utc->getTimestamp() - ($off * 60));
		return $local;
	}

	public function _action_wp_footer(){
		printf(
			'
			<script type="text/javascript">
			jQuery(document).ready(function($){

				var d = new Date();
				var o = d.getTimezoneOffset();
				d.setTime(d.getTime() + ( 30 * 24 * 60 * 60 * 1000) );
				var expires = "expires=" + d.toGMTString();
				document.cookie = "%s=" + o + "; " + expires + ";path=/";

			});
			</script>
			',
			self::CK_TZ

		);
	}

	public function _action_wp_enqueue_scripts(){
		wp_enqueue_script('jquery');
	}
} 