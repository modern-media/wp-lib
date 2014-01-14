<?php
/**
 * Created by JetBrains PhpStorm.
 * User: peter
 * Date: 9/20/13
 * Time: 3:26 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ModernMedia\MustUse\SocialSharing;


class SocialSharingOptions {
	public $twitter_via_screen_name;
	public $twitter_related_screen_name;
	public $twitter_hashtag;
	public $twitter_count_box = "none";
	public $twitter_button_size = "medium";
	public $twitter_include_script = false;
	public $google_plusone_size = "medium";
	public $google_plusone_annotation = "none";
	public $google_plusone_include_script = false;
	public $su_badge_layout = "5";
	public $su_badge_include_script = false;
	public $fb_app_id = '';
	public $fb_send = "false";
	public $fb_layout = "standard";
	public $fb_show_faces = false;
	public $fb_action = "like";
	public $fb_font = "arial";
	public $fb_colorscheme = "light";
	public $fb_include_script = false;
	public $linkedin_layout = "none";
	public $linkedin_include_script = false;
	public $pinterest_layout = "none";
	public $pinterest_include_script = false;

	public function init_from_array($a, &$errors){
		$vars = get_class_vars(get_class($this));
		foreach($vars as $name=>$ignore){
			if (isset($a[$name])){
				$this->{$name} = ($a[$name]);
			}
		}
		$this->twitter_via_screen_name = trim($this->twitter_via_screen_name);
		$this->twitter_via_screen_name = ltrim($this->twitter_via_screen_name, "@");

		$rel_screen_names = explode(",", $this->twitter_related_screen_name);
		foreach ($rel_screen_names as $n=>$v){
			$parts = array_map("trim", explode(":", $v));
			$parts[0] = ltrim($parts[0], "@");
			$rel_screen_names[$n] = implode(":", $parts);
		}
		$this->twitter_related_screen_name = implode(",", $rel_screen_names);
		$this->twitter_hashtag = ltrim($this->twitter_hashtag, "#");
		if (! in_array($this->twitter_count_box, self::twitter_count_box_options())){
			$this->twitter_count_box = "none";
		}
		if (! in_array($this->twitter_button_size, self::twitter_button_size_options())){
			$this->twitter_button_size = "medium";
		}

		if (! in_array($this->google_plusone_annotation, self::get_google_plusone_annotation_options())){
			$this->google_plusone_annotation = "none";
		}
		if (! in_array($this->google_plusone_size, self::get_google_plusone_size_options())){
			$this->google_plusone_size = "medium";
		}

		if (! in_array($this->su_badge_layout, self::get_su_badge_layouts())){
			$this->su_badge_layout = "5";
		}

		if(isset($a['twitter_include_script']) && "1" == $a['twitter_include_script']){
			$this->twitter_include_script = true;
		} else {
			$this->twitter_include_script = false;
		}
		if(isset($a['google_plusone_include_script']) && "1" == $a['google_plusone_include_script']){
			$this->google_plusone_include_script = true;
		} else {
			$this->google_plusone_include_script = false;
		}
		if(isset($a['su_badge_include_script']) && "1" == $a['su_badge_include_script']){
			$this->su_badge_include_script = true;
		} else {
			$this->su_badge_include_script = false;
		}
		if(isset($a['fb_include_script']) && "1" == $a['fb_include_script']){
			$this->fb_include_script = true;
		} else {
			$this->fb_include_script = false;
		}
		if(isset($a['linkedin_include_script']) && "1" == $a['linkedin_include_script']){
			$this->linkedin_include_script = true;
		} else {
			$this->linkedin_include_script = false;
		}
		if(isset($a['pinterest_include_script']) && "1" == $a['pinterest_include_script']){
			$this->pinterest_include_script = true;
		} else {
			$this->pinterest_include_script = false;
		}

		$rx = "/^\w*$/";
		$vars = array(
			"twitter_via_screen_name",
			"twitter_hashtag"
		);
		foreach($vars as $name){
			if (! preg_match($rx, $this->{$name})){
				$errors[$name] = "Illegal characters in " . implode( " ", array_map("ucfirst", explode("_", $name)));
			}
		}

		return count($errors) ? false : true;
	}


	public static function twitter_count_box_options (){
		return array(
			"none",
			"horizontal",
			"vertical"
		);
	}
	public static function twitter_button_size_options (){
		return array(
			"medium",
			"large",
		);
	}

	public static function get_google_plusone_size_options(){
		return array(
			"small",
			"medium",
			"standard",
			"tall"
		);
	}
	public static function get_google_plusone_annotation_options(){
		return array(
			"none",
			"bubble",
			"inline"

		);
	}
	public static function get_su_badge_layouts(){
		return array(1,2,3,4,5,6);
	}

	public static function get_fb_colorschemes(){
		return array("light", "dark");
	}
	public static function get_fb_actions(){
		return array("like", "recommend");
	}
	public static function get_fb_fonts(){
		return array("arial", "lucida grande", "segoe ui", "tahoma", "tebuchet ms", "verdana");
	}
	public static function  get_fb_tf_opts(){
		return array("false", "true");
	}
	public static function  get_fb_layout_opts(){
		return array("standard", "button_count", "box_count");
	}
	public  static function get_linkedin_layout_opts(){
		return array("none", "top", "right");
	}
	public  static function get_pinterest_layout_opts(){
		return array("none", "above", "beside");
	}
}