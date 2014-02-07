<?php
namespace ModernMedia\WPLib;

class DomainMapper {

	/**
	 * @var array
	 */
	private $map = null;

	/**
	 * @var string
	 */
	private $domain = '';

	/**
	 * @var int
	 */
	private $blog_id = 0;


	public function __construct(){
		global $wpdb, $current_blog, $blog_id, $site_id, $current_site;
		/** @var \wpdb $wpdb */
		if (isset($GLOBALS['modern_media_domain_map'])){
			$this->domain = $_SERVER[ 'HTTP_HOST' ];
			$this->map = $GLOBALS['modern_media_domain_map'];
			$this->blog_id = $this->map[$this->domain];
			$sql = sprintf('SELECT * FROM %s WHERE blog_id =%s', $wpdb->blogs, $this->blog_id);
			$current_blog = $wpdb->get_row($sql);
			$current_blog->domain = $_SERVER[ 'HTTP_HOST' ];
			$current_blog->path = '/';
			$blog_id = $this->blog_id;
			$site_id = $current_blog->site_id;
			define( 'COOKIE_DOMAIN', $_SERVER[ 'HTTP_HOST' ] );
			$sql = sprintf('SELECT * FROM %s WHERE id =%s', $wpdb->site, $current_blog->site_id);
			$current_site = $wpdb->get_row($sql);
			$current_site->blog_id = 1;
			$current_site = get_current_site_name( $current_site );
			add_action('plugins_loaded', array($this, '_action_plugins_loaded'));
		}

	}

	public function _action_plugins_loaded(){
		add_filter('option_home', array($this, '_filter_url'),  9999, 1);
		add_filter('option_siteurl', array($this, '_filter_url'),  9999, 1);
	}

	public function _filter_url($url){
		$blog_id = get_current_blog_id();
		$domain = array_search($blog_id, $this->map);
		if (! $domain){
			return $url;
		}
		$url = is_ssl() ? 'https://' : 'http://';
		$url .= $domain;
		return $url;
	}
} 