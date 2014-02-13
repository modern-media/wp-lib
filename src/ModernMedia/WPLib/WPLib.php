<?php
namespace ModernMedia\WPLib;
use ModernMedia\WPLib\Admin\Panel\WPLibSettingsPanel;
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
		add_action('widgets_init', array($this, '_action_widgets_init'));
	}
	public function _action_muplugins_loaded(){


		if (is_admin()){
			new WPLibSettingsPanel;
		}

		if ($this->settings->component_enabled_mailer){
			Mailer::inst();
		}

		if ($this->settings->component_enabled_bitly_url_shortening){
			BitlyURLShortening::inst();
		}

		MetaTags::inst();
		Carousel::inst();
		Stylesheet::inst();
		SocialSharing::inst();
		AWSS3::inst();
		Debugger::inst();


		if ($this->settings->component_enabled_shared_sidebars){
			NetworkSidebarSharing::inst();
		}

	}

	public function _action_widgets_init(){
		$widgets = $this->get_widgets();
		foreach($widgets as $key => $ignore){
			if (in_array($key, $this->settings->enabled_widgets)){
				register_widget('\\ModernMedia\WPLib\\Widget\\' . $key);
			}
		}
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


	public function get_widgets(){
		return array(
			'CarouselWidget' => array(
				'name' => __('Carousel'),
				'description' => __('Put a carousel in a widget.')
			),
			'CopyrightWidget' => array(
				'name' => __('Copyright'),
				'description' => __('Always have an updated copyright.')
			),
			'ThumbnailWidget' => array(
				'name' => __('Thumbnail'),
				'description' => __('An image, some text.')
			),

			'SearchWidget' => array(
				'name' => __('Super Search'),
				'description' => __('A slightly better search widget.')
			),
			'SingleLinkWidget' => array(
				'name' => __('Super Powered Single Link'),
				'description' => __('Displays a link to one af a variety of things.')
			),
			'SinglePostWidget' => array(
				'name' => __('Super Powered Single Post'),
				'description' => __('Displays a single post or custom post type.')
			),
			'TitleAndTaglineWidget' => array(
				'name' => __('Site Name and Tagline'),
				'description' => __('Puts your site\'s name and tagline in a widget.')
			),

			'TextWidget' => array(
				'name' => __('Super Text'),
				'description' => __('A much better text widget.')
			),
			'TwitterFollowWidget' => array(
				'name' => __('Twitter Follow Button'),
				'description' => __('Displays a Twitter follow button.')
			),

		);
	}

}