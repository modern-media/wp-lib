<?php
namespace ModernMedia\WPLib\SocialSharing;


use ModernMedia\WPLib\Utils;

class SocialSharing {
	const PLUGIN_NAMESPACE = "SocialSharing";

	public function __construct(){
		add_action("plugins_loaded", array($this, "_action_plugins_loaded"));
	}

	/**
	 * @return SocialSharingOptions
	 */
	public static function get_options(){
		$options = get_option(self::PLUGIN_NAMESPACE);
		if(! is_a($options, "ModernMedia\\MustUse\\SocialSharing\\SocialSharingOptions")){
			$options = new SocialSharingOptions();
		}
		return $options;
	}

	public function _action_plugins_loaded(){
		add_action("admin_menu", array($this, "_action_admin_menu"));
		add_filter("user_contactmethods", array($this, "_filter_user_contactmethods"));
		add_action("wp_footer", array($this, "_action_wp_footer"));
	}

	public function _filter_user_contactmethods($arr){
		$arr["twitter"] = __("Twitter");
		$arr["google_plus"] = __("Google+ Profile URL");
		return $arr;
	}

	public function _action_admin_menu(){
		$t = "Social Sharing";
		add_options_page($t, $t, "administrator", self::PLUGIN_NAMESPACE, array($this, "admin") );
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

	public static function tweetButton($url, $tweet_text, $options = null){
		/* Most Recent as of Oct 01, 2013
		<a href="https://twitter.com/share" class="twitter-share-button" data-via="peterwhitesell">Tweet</a>
		*/
		if (is_null($options)) $options = self::get_options();
		$html = "<a href=\"https://twitter.com/share\" class=\"twitter-share-button\"";
		$html .= " data-url=\"{$url}\"";
		$html .= " data-text=\"" . esc_attr($tweet_text) . "\"";
		$html .= " data-size=\"{$options->twitter_button_size}\"";
		$html .= " data-count=\"{$options->twitter_count_box}\"";
		if (! empty($options->twitter_via_screen_name)){
			$html .= " data-via=\"{$options->twitter_via_screen_name}\"";
		}
		if (! empty($options->twitter_related_screen_name)){
			$html .= " data-related=\"{$options->twitter_related_screen_name}\"";
		}
		if (! empty($options->twitter_hashtag)){
			$html .= " data-hashtags=\"{$options->twitter_hashtag}\"";
		}
		$html .= ">Tweet</a>";
		return $html;
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
		require Utils::get_lib_path("includes/templates/sharethis.php");
		return ob_get_clean();
	}
}