<?php
namespace ModernMedia\WPLib;
use ModernMedia\WPLib\Admin\WPLibSettingsPanel;
use ModernMedia\WPLib\MetaTags\MetaTags;
use ModernMedia\WPLib\Widget\Widgets;
use ModernMedia\WPLib\Carousel\Carousel;
use ModernMedia\WPLib\SocialSharing\SocialSharing;
use ModernMedia\WPLib\SocialSharing\ShareThis;
use ModernMedia\WPLib\Data\WPLibSettings;
class WPLib {

	const OK = 'mm-wp-lib-settings';

	private static $instance;

	/**
	 * @var WPLibSettings
	 */
	private $settings;

	/**
	 * @return WPLib
	 */
	public static function inst(){
		if (! self::$instance instanceof WPLib){
			self::$instance = new WPLib;
		}
		return self::$instance;
	}

	public function __construct(){
		add_action('muplugins_loaded', array($this, '_action_muplugins_loaded'));
		if (is_multisite()){
			$this->settings = get_site_option(self::OK);
		} else {
			$this->settings = get_option(self::OK);
		}
		if (! $this->settings instanceof WPLibSettings){
			$this->settings = new WPLibSettings;
		}
	}
	public function _action_muplugins_loaded(){
		if (is_admin()){
			new WPLibSettingsPanel;
		}
		MetaTags::inst();
		Widgets::inst();
		Carousel::inst();
		Stylesheet::inst();
		SocialSharing::inst();
		AWSS3::inst();
		ShareThis::inst();

	}

	/**
	 * @return WPLibSettings
	 */
	public function get_settings(){
		return $this->settings;
	}

	/**
	 * @param $arr
	 */
	public function set_settings($arr){
		$this->settings = new WPLibSettings($arr);
		if (is_multisite()){
			update_site_option(self::OK, $this->settings);
		} else {
			update_option(self::OK, $this->settings);
		}

	}

	public function get_components(){

	}
} 