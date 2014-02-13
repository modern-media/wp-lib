<?php
namespace ModernMedia\WPLib;

class SocialSharing {

	const STUMBLEUPON = 'stumbeleupon';
	const FACEBOOK = 'facebook';
	const TWITTER = 'twitter';
	const LINKEDIN = 'linkedin';
	const GOOGLEPLUS = 'googleplus';
	const PINTEREST = 'pinterest';
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

	public static function get_platforms(){
		return array(

		);
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
		$arr["facebook"] = __("Facebook Profile URL");
		return $arr;
	}











	public static function get_share_bar($post_id){
		ob_start();
		require Utils::get_lib_path('includes/templates/sharethis.php');
		return ob_get_clean();
	}


	/**
	 * Get a share button. References:
	 * Facebook: https://developers.facebook.com/docs/plugins/like-button
	 * LinkedIn: https://developer.linkedin.com/share-plugin-reference
	 * Twitter: https://dev.twitter.com/docs/tweet-button
	 * StumbleUpon: http://www.stumbleupon.com/dt/badges/create
	 * Google +: https://developers.google.com/+/web/share/
	 *
	 * @param $service
	 * @param $post
	 * @param array $attrs
	 * @return string
	 */
	public function get_platform_share_button($service, $post, $attrs = array()){
		$inside = '';
		switch ($service){
			case self::STUMBLEUPON:
				$defaults = array(
					'layout' => 1,
					'location' => get_permalink($post->ID)
				);
				$tag = 'su:badge';
				break;
			case self::FACEBOOK:
				$defaults = array(
					'class' => 'fb-share-button',
					'data-href' => get_permalink($post->ID),
					'data-layout' => 'button',
					'data-colorscheme' => 'light',
					 'data-action' => 'recommend',
					 'data-show-faces' => 'false',
					 'data-share' => 'true',
				);
				$tag = 'div';
				break;
			case self::TWITTER:
				$defaults = array(
					'href' => 'https://twitter.com/share',
					'class' => 'twitter-share-button',
					'data-url' => wp_get_shortlink($post->ID),
					'data-counturl' =>  get_permalink($post->ID),
					'data-text' => get_the_title($post->ID),
					'data-related' => $this->options->twitter_data_related,
					'data-hashtags' => $this->options->twitter_data_hashtags,
					'data-count' => 'none',
					'data-size' => 'medium',
				);
				$tag = 'a';
				$inside = __('Tweet');
				break;
			case self::LINKEDIN:
				$defaults = array(
					'type' => 'IN/Share',
					'data-url' => get_permalink($post->ID),
					'data-counter' => '',

				);
				$tag = 'script';
				break;
			case self::GOOGLEPLUS:
				$defaults = array(
					'class' => 'g-plus',
					'data-action' => 'share',
					'data-href' =>  get_permalink($post->ID),
					'data-annotation' =>  'none',
					'data-size' =>  'medium',
				);
				$tag = 'div';
				break;
			case self::PINTEREST:
				if (! isset($attrs['url'])){
					$attrs['url'] = get_permalink($post->ID);
				}
				if (! isset($attrs['media'])){
					if (! has_post_thumbnail($post->ID)) return '';
					$attrs['media'] = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
				}

				$defaults = array(
					'data-pin-do' => 'buttonPin',
					'data-pin-config' => 'beside',
					'data-pin-color' =>'red',
					'data-pin-height' => '23',
				);
				$attrs = array_merge($defaults, $attrs);
				$href = '//www.pinterest.com/pin/create/button/?url=' .
					urlencode($attrs['url']) . '&media=' . urlencode($attrs['media']);
				if (isset($attrs['description'])){
					$href .= '&description=' .  urlencode($attrs['description']);
				}
				unset($attrs['url']);
				unset($attrs['media']);
				unset($attrs['description']);
				$attrs['href'] = $href;
				$tag = 'a';
				$inside = __('Pin It');
				break;
			default:
				return '';

		}
		$attrs = array_merge($defaults, $attrs);
		return '<span class="wp-mm-lib-share-button">' . HTML::tag($tag, $attrs) . $inside . HTML::end_tag($tag) . '</span>';
	}


	public function get_raw_share_link($service, $post){
		$params = array();
		$title = '';
		switch ($service){

			case self::FACEBOOK:
				$params['app_id'] = $this->options->facebook_app_id;
				$params['u'] = get_permalink($post->ID);
				$url = 'https://www.facebook.com/sharer/sharer.php';
				$title = __('Share on Facebook');
				break;

			case self::TWITTER:
				$params['text'] = get_the_title($post);
				$params['url'] = get_permalink($post);
				$url = 'https://twitter.com/share';
				$title = __('Tweet');
				break;

			case self::LINKEDIN:
				$url = 'http://www.linkedin.com/cws/share';
				$params['url'] = get_permalink($post);
				break;
			case self::GOOGLEPLUS:
				$url = 'https://plus.google.com/share';
				$params['url'] = get_permalink($post);
				break;
			default:
				return '';

		}
		$url = add_query_arg($params, $url);

		$attrs = array(
			'href' => $url,
			'class' => 'wp-mm-lib-share-link ' . $service,
			'target' => '_blank',
			'title' => $title,

		);
		return  HTML::tag('a', $attrs) . '<span>' . $title . '</span>' . HTML::end_tag('a');
	}



}