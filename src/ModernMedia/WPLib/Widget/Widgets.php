<?php
namespace ModernMedia\MustUse\Widget;
/**
 * Class Widgets
 * @package ModernMedia\MustUse\Widget
 */
class Widgets {

	const OK_ENABLE_DATA_ICONS = "mmmu_enable_single_link_data_icon";
	const OK_DATA_ICON_CSS_PATH = "mmmu_enable_single_link_data_icon_css_pathe";
	/**
	 * @var Widgets
	 */
	private static $instance;

	/**
	 * @return Widgets
	 */
	public static function inst(){
		if (! self::$instance instanceof Widgets){
			self::$instance = new Widgets();
		}
		return self::$instance;
	}

	private function __construct(){
		add_action('plugins_loaded', array($this, '_action_plugins_loaded'));

	}

	
	public function _action_plugins_loaded(){
		add_action('widgets_init',  array($this, '_action_widgets_init'));
	}



	public function _action_widgets_init(){
		register_widget('\\ModernMedia\\MustUse\\Widget\\Text');
		register_widget('\\ModernMedia\\MustUse\\Widget\\Search');
		register_widget('\\ModernMedia\\MustUse\\Widget\\Carousel');
		register_widget('\\ModernMedia\\MustUse\\Widget\\SingleLink');
		register_widget('\\ModernMedia\\MustUse\\Widget\\SinglePost');
		register_widget('\\ModernMedia\\MustUse\\Widget\\TitleAndTagline');
		register_widget('\\ModernMedia\\MustUse\\Widget\\Copyright');
	}



}
