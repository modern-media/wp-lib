<?php
namespace ModernMedia\WPLib\SocialSharing;
use ModernMedia\WPLib\Scripts;
use ModernMedia\WPLib\SocialSharing\Data\SocialSharingOptions;
use ModernMedia\WPLib\SocialSharing\Admin\SocialSharingOptionsPanel;
use ModernMedia\WPLib\SocialSharing\Data\TweetButtonParams;
use ModernMedia\WPLib\Utils;

class SocialSharing {
	const PLUGIN_NAMESPACE = "SocialSharing";
	const OK = 'mm_wp_lib_social_sharing';

	/**
	 * @var SocialSharing
	 */
	private static $instance;

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
		if (is_admin()) new SocialSharingOptionsPanel;
		add_action("plugins_loaded", array($this, "_action_plugins_loaded"));
	}

	/**
	 * @return SocialSharingOptions
	 */
	public function get_options(){
		$o = get_option(self::OK);
		if(! $o instanceof SocialSharingOptions){
			$o = new SocialSharingOptions();
		}
		return $o;
	}

	public function _action_plugins_loaded(){
		add_action('widgets_init',  array($this, '_action_widgets_init'));
		add_action('wp_enqueue_scripts', function(){
			Scripts::inst()->enqueue(Scripts::SOCIAL_SHARING_ASYNC);
		});
	}

	public function _action_widgets_init(){
		register_widget('\\ModernMedia\\WPLib\\SocialSharing\\Widget\\TwitterFollowWidget');
	}

	public function _filter_user_contactmethods($arr){
		$arr["twitter"] = __("Twitter");
		$arr["google_plus"] = __("Google+ Profile URL");
		return $arr;
	}



	public function admin(){

		$options = self::get_options();

		$errors = array();
		$message = "";

		if (isset($_POST) && isset($_POST["submitting"]) && $_POST["submitting"] == 1){
			check_admin_referer(self::PLUGIN_NAMESPACE);
			if ($options->init_from_array($_POST, $errors)){
				$message = "Options saved.";
				update_option(self::PLUGIN_NAMESPACE, $options);
			}
		}
		require Utils::get_lib_path('/includes/admin/panel/social_sharing_options.php');
	}

	public function _action_wp_footer(){

		$options = self::get_options();
		$fb_app_id = $options->fb_app_id;

		/* Twitter Share */
		if($options->fb_include_script){
			?>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		<?php
		}

		/* Google plusone asynchronous */
		if($options->fb_include_script){
			?>
			<script type="text/javascript">
				window.___gcfg = {
					lang: 'en-US',
					parsetags: 'onload'
				};
				(function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				})();
			</script>
		<?php
		}

		/* Stumbleupon Badge */
		if($options->su_badge_include_script){
			?>
			<script type="text/javascript">
				(function() {
					var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
					li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
				})();
			</script>
		<?php
		}

		/* FB Like */
		if($options->fb_include_script){
			?><div id="fb-root"></div>
			<script>(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $fb_app_id ?>";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
		<?php
		}

		/* Linkedin Share */
		if($options->linkedin_include_script){
			?>
			<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
		<?php
		}


		/* Pinterest Pin it */
		if($options->pinterest_include_script){
			?>
			<script type="text/javascript">
				(function(d){
					var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
					p.type = 'text/javascript';
					p.async = true;
					p.src = '//assets.pinterest.com/js/pinit.js';
					f.parentNode.insertBefore(p, f);
				}(document));
			</script>
		<?php
		}
	}

	/**
	 * @param null|array|TweetButtonParams $params
	 * @param string $text
	 * @return string
	 */
	public function get_tweet_button($params = null, $text = ''){
		if (is_array($params)){
			$params = new TweetButtonParams($params);
		} elseif (! $params instanceof TweetButtonParams){
			$params = new TweetButtonParams();
		}
		$defaults = $this->get_options()->tweet_button;
		$attrs = array(
			'href' => 'https://twitter.com/share',
			'class' => 'twitter-share-button'
		);

		foreach($params->get_keys() as $key){
			if (! empty($params->{$key})){
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
	/**
	 * @static
	 * @param $url
	 * @param SocialSharingOptions $options
	 * @return bool
	 */
	public static function googlePlusOneButton($url, $options = null){
		if (is_null($options)) $options = self::get_options();
		$html = "<div class=\"g-plusone\"";
		$html .= " data-href=\"{$url}\"";
		$html .= " data-annotation=\"{$options->google_plusone_annotation}\"";
		$html .= " data-size=\"{$options->google_plusone_size}\"";
		$html .= "></div>";
		return $html;
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