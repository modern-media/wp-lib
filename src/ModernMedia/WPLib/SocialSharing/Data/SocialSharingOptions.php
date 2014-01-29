<?php
namespace ModernMedia\WPLib\SocialSharing\Data;
use ModernMedia\WPLib\Data\BaseData;

class SocialSharingOptions extends BaseData {

	/**
	 * @var TweetButtonParams
	 */
	public $tweet_button;

	public $google_plus_size = "medium";
	public $google_plus_annotation = "none";

	public $su_badge_layout = "5";
	public $su_badge_include_script = false;

	public $fb_app_id = '';
	public $fb_send = "false";
	public $fb_layout = "standard";
	public $fb_show_faces = false;
	public $fb_action = "like";
	public $fb_font = "arial";
	public $fb_colorscheme = "light";


	public $linkedin_layout = "none";
	public $pinterest_layout = "none";
	public $pinterest_include_script = false;

	public function __construct($init = null,  $whitelist = array(), $blacklist = array()){
		$this->tweet_button = new TweetButtonParams();
		parent::__construct($init, $whitelist, $blacklist);

	}
	/**
	 * @param array $arr
	 * @param array $whitelist
	 * @param array $blacklist
	 */
	public function init_from_user_data($arr, $whitelist = array(), $blacklist = array()){
		parent::init_from_user_data($arr, $whitelist, $blacklist);
		$this->tweet_button = new TweetButtonParams(isset($arr['tweet_button']) ? $arr['tweet_button'] : null);
	}

}