<?php
namespace ModernMedia\WPLib;
use ModernMedia\WPLib\Data\GooglePlusShareButtonParams;
use ModernMedia\WPLib\Data\TwitterShareButtonParams;

class SocialSharing {

	/**
	 * @var SocialSharing
	 */
	private static $instance;

	/**
	 * @var Data\WPLibSettings
	 */
	protected $options;

	/**
	 * @return SocialSharing
	 */
	public static function inst(){
		if (! self::$instance instanceof SocialSharing){
			self::$instance = new SocialSharing;
		}
		return self::$instance;
	}

	private function __construct(){
		$this->options = WPLib::inst()->get_settings();
		add_action("plugins_loaded", array($this, "_action_plugins_loaded"));
	}



	public function _action_plugins_loaded(){
		//add_action('widgets_init',  array($this, '_action_widgets_init'));
		add_action('wp_enqueue_scripts', function(){
			Scripts::inst()->enqueue(Scripts::SOCIAL_SHARING_ASYNC);
			Scripts::inst()->enqueue(Scripts::LINKED_IN);
		});
		add_action('wp_head', array($this, '_action_wp_head'));
	}

	public function _action_wp_head(){
		if ($this->options->enable_share_this && ! empty($this->options->share_this_publisher_key)){
			printf(
				'

				<script type="text/javascript">var switchTo5x=true;</script>
				<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
				<script type="text/javascript">stLight.options({publisher: "%s", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>

				',
				$this->options->share_this_publisher_key
			);
		}
		if (! empty($this->options->facebook_app_id)){
			printf(
				'
				<script type="text/javascript">var mm_wp_lib_social_sharing_facebook_app_id = "%s";</script>

				',
				$this->options->facebook_app_id
			);
		}

		return;

	}

	public function _action_widgets_init(){
		register_widget('\\ModernMedia\\WPLib\\SocialSharing\\Widget\\TwitterFollowWidget');
	}

	public function _filter_user_contactmethods($arr){
		$arr["twitter"] = __("Twitter");
		$arr["google_plus"] = __("Google+ Profile URL");
		return $arr;
	}


	/**
	 * @param null|array|TwitterShareButtonParams $params
	 * @param string $text
	 * @return string
	 */
	public function get_tweet_button($params = null, $text = ''){
		if (is_array($params)){
			$params = new TwitterShareButtonParams($params);
		} elseif (! $params instanceof TwitterShareButtonParams){
			$params = new TwitterShareButtonParams();
		}
		$defaults = $this->get_options()->tweet_button;
		$attrs = array(
			'href' => 'https://twitter.com/share',
			'class' => 'twitter-share-button'
		);

		foreach($params->get_keys() as $key){
			if (empty($params->{$key})){
				$params->{$key} = $defaults->{$key};
			}
			if (! empty($params->{$key})){
				switch($key){
					case 'url':
					case 'counturl':
						$attrs['data-' . $key] = $params->{$key};
						break;
					default:
						$attrs['data-' . $key] = esc_attr($params->{$key});
						break;
				}

			}
		}

		foreach($attrs as $key => $val){
			$attrs[$key] = sprintf('%s="%s"', $key, $val);
		}
		$attrs = implode(' ', $attrs);
		if (empty($text)) {
			$text = __('Tweet');
		}
		return sprintf('<a %s>%s</a>', $attrs, $text);
	}
	public function get_tweet_button_for_post($post_id, $params = null){
		if (is_null($params)){
			$params = new TwitterShareButtonParams;
		}
		if(empty($params->text)){
			$params->text = get_the_title($post_id);
		}
		if(empty($params->url)){
			$params->url = wp_get_shortlink($post_id);
			$params->counturl = get_permalink($post_id);
		}
		return $this->get_tweet_button($params);
	}

	public function get_google_plus_button($params = null){
		if (is_array($params)){
			$params = new GooglePlusShareButtonParams($params);
		} elseif (! $params instanceof GooglePlusShareButtonParams){
			$params = new GooglePlusShareButtonParams();
		}
		$attrs = array(
			'data-href' => $params->href,
			'class' => 'g-plusone',
			'data-annotation' => 'none',
			'data-size' => 'medium'
		);
		foreach($attrs as $key => $val){
			$attrs[$key] = sprintf('%s="%s"', $key, $val);
		}
		$attrs = implode(' ', $attrs);

		return sprintf('<div %s></div>', $attrs);
	}
	public function get_google_plus_button_for_post($post_id, $params = null){
		if (is_null($params)){
			$params = new GooglePlusShareButtonParams;
		}
		if(empty($params->text)){
			$params->href = get_permalink($post_id);
		}

		return $this->get_google_plus_button($params);
	}




	/**
	 * @static
	 * @param $url
	 * @param SocialSharingOptions $options
	 * @return bool
	 */
	public static function stumbleUponBadge($url, $options = null){
		if (is_null($options)) $options = self::get_options();
		$html = "<su:badge";
		$html .= " layout=\"{$options->su_badge_layout}\"";
		$html .= " location=\"{$url}\"";
		$html .= "></su:badge>";
		return $html;
	}

	public static function  fbLike($url, $options){
		if (is_null($options)) $options = self::get_options();
		$html = "<div class=\"fb-like\"";
		$html .= " data-href=\"{$url}\"";
		$send = $options->fb_send == "true" ? "true" : "false";
		$html .= " data-send=\"" . $send . "\"";
		$html .= " data-layout=\"" . $options->fb_layout . "\"";
		$show_faces = $options->fb_show_faces == "true" ? "true" : "false";
		$html .= " data-show-faces=\"" . $show_faces . "\"";
		$html .= " data-action=\"" . $options->fb_action . "\"";
		$html .= " data-font=\"" . $options->fb_font . "\"";
		$html .= " data-colorscheme=\"" . $options->fb_colorscheme . "\"";
		$html .= "></div>";
		return $html;
	}

	public static function linkedInShare($url, $options){
		if (is_null($options)) $options = self::get_options();
		$html = "<script type=\"IN/Share\"";
		$html .= " data-url=\"{$url}\"";
		$html .= " data-counter=\"$options->linkedin_layout\"";
		$html .= "></script>";
		return $html;
	}

	public static function pinterestShare($url, $img, $excerpt, $options = null){
		if (is_null($options)) $options = self::get_options();
		$html = "<a class=\"pin-it-button\" href=\"http://pinterest.com/pin/create/button/?url=" . urlencode($url);
		$html .= "&media=" . urlencode($img);
		$html .= "&description=" . urlencode($excerpt);
		$html .= "\"";
		$html .= " data-pin-config=\"{$options->pinterest_layout}\">Pin It</a>";
		return $html;
	}

	public static function get_share_bar($post_id){
		ob_start();
		require Utils::get_lib_path('includes/templates/sharethis.php');
		return ob_get_clean();
	}

	public function get_twitter_count_box_options (){
		return array(
			'none' => __('None'),
			'horizontal' => __('Horizontal'),
			'vertical' => __('Vertical'),
		);
	}
	public function get_twitter_button_size_options (){
		return array(
			'medium' => __('Medium'),
			'large' => __('Large'),
		);
	}

	public function get_google_plusone_size_options(){
		return array(
			'small' => __('Small'),
			'medium' => __('Medium'),
			'standard' => __('Standard'),
			'tall' => __('Tall'),
		);
	}
	public function get_google_plusone_annotation_options(){
		return array(
			'none' => __('None'),
			'bubble' => __('Bubble'),
			'inline' => __('Inline')

		);
	}
	public function get_su_badge_layouts(){
		return array(1,2,3,4,5,6);
	}

	public function get_fb_colorschemes(){
		return array("light", "dark");
	}
	public function get_fb_actions(){
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

	/**
	 * @param $post
	 * @return string
	 */
	public function get_share_this_button($post){
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