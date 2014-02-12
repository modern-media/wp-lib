<?php
namespace ModernMedia\WPLib;

class BitlyURLShortening {

	const PMK = 'mm-wp-lib-bitly-short-url';
	const SHORTEN_URL = 'https://api-ssl.bitly.com/v3/shorten';
	/**
	 * @var BitlyURLShortening
	 */
	private static $instance = null;

	/**
	 * @return BitlyURLShortening
	 */
	public static function inst(){
		if (! self::$instance instanceof BitlyURLShortening){
			self::$instance = new BitlyURLShortening;
		}
		return self::$instance;
	}


	/**
	 * Singleton constructor...
	 */
	private function __construct(){
		add_action('plugins_loaded', array($this, '_action_plugins_loaded'));

	}

	public function _action_plugins_loaded(){

		add_filter('pre_get_shortlink', array($this, '_filter_pre_get_shortlink'), 10, 4);
	}

	/**
	 * Queries the db or bit.ly for a short url...
	 * @see wp_get_shortlink()
	 * 
	 * @param $link
	 * @param int $id
	 * @param string $context
	 * @return mixed|string
	 */
	public function _filter_pre_get_shortlink($link, $id = 0, $context = 'post'){
		global $wp_query;

		$post = false;
		if ( 'query' == $context && is_singular() ) {
			$post = get_post( $wp_query->get_queried_object_id() );
		} elseif ( 'post' == $context ) {
			$post = get_post( $id );
		}
		if (! $post) return $link;


		$url = get_post_meta($post->ID, self::PMK, true);
		if ($url) {
			return $url;
		}

		$opts = WPLib::inst()->get_settings();
		if (! $opts->component_enabled_bitly_url_shortening || empty($opts->bitly_access_token)){
			return $link;
		}

		$params = array(
			'longUrl' => get_permalink($post->ID),
			'access_token' => $opts->bitly_access_token,
		);
		$url = add_query_arg($params, self::SHORTEN_URL);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$resp = json_decode(curl_exec($ch));
		if (! is_object($resp) || ! isset($resp->status_code)  || 200 != $resp->status_code ){
			return $link;
		}

		$url = $resp->data->url;
		update_post_meta($post->ID, self::PMK, $url);
		return $url;

	}
}

