<?php
namespace ModernMedia\WPLib;

class Stylesheet {


	/**
	 * @var Stylesheet
	 */
	private static $instance = null;

	/**
	 * @return Stylesheet
	 */
	public static function inst(){
		if (! self::$instance instanceof Stylesheet){
			self::$instance = new Stylesheet;
		}
		return self::$instance;
	}


	private function __construct(){
		if (is_admin()){
			add_action('admin_enqueue_scripts', function(){
				wp_enqueue_style('mm-wp-lib-admin-css', Utils::get_lib_uri('assets/css/admin.min.css'));
			});
		}
	}
} 