<?php
namespace ModernMedia\WPLib\MetaTags;

use ModernMedia\WPLib\MetaTags\Admin\MetaTagsMetaBox;
use ModernMedia\WPLib\MetaTags\Admin\SiteMetaTagsSettingsPanel;
use ModernMedia\WPLib\MetaTags\Data\PostMetaData;
use ModernMedia\WPLib\MetaTags\Data\SiteMetaSettings;

class MetaTags {

	const OK_SITE_META = 'modern_media_wp_lib_site_meta';
	const PMK_META_TAGS = 'modern_media_wp_lib_meta_tags';

	const OG_IMAGE_WIDTH = 1200;
	const OG_IMAGE_HEIGHT = 630;

	const OG_IMAGE_SIZE_ID = 'modern_media_wp_lib_og_image_size';

	/**
	 * @var MetaTags
	 */
	private static $instance = null;

	/**
	 * @return MetaTags
	 */
	public static function inst(){
		if (! self::$instance instanceof MetaTags){
			self::$instance = new MetaTags;
		}
		return self::$instance;
	}

	private function __construct(){
		new MetaTagsMetaBox();
		new SiteMetaTagsSettingsPanel();
		add_action('plugins_loaded', array($this, '_action_plugins_loaded'));

	}

	public function _action_plugins_loaded(){
		add_action('wp_head', array($this, '_action_wp_head'));
		add_action('after_setup_theme', array($this, '_action_after_setup_theme'));
	}

	public function _action_after_setup_theme(){
		$o = $this->get_option_site_meta();
		add_image_size($o->og_image_width, $o->og_image_height, false);
	}

	/**
	 * @param string $str
	 * @param string $default
	 * @return string
	 */
	protected function clean_string($str = '', $default = ''){
		$str = trim(strip_tags($str));
		$str = preg_replace('/\w+/', ' ', $str);
		if (empty($str)){
			$str = $default;
			$str = trim(strip_tags($str));
			$str = preg_replace('/\w+/', ' ', $str);
		}
		return esc_attr($str);
	}
	/**
	 * Add the meta tags according to the page context...
	 */
	public function _action_wp_head(){
		$options = $this->get_option_site_meta();
		$ogs = array(
			'og:site_name' => $this->clean_string(get_bloginfo('name'))
		);
		if ($options->default_site_og_image_id > 0){
			$arr = wp_get_attachment_image_src($options->default_site_og_image_id, self::OG_IMAGE_SIZE_ID);
			if (! empty($arr[0])){
				$ogs['og:image'] = $arr[0];
				$ogs['og:image:width'] = isset($arr[1]) ? $arr[1] : '';
				$ogs['og:image:height'] = isset($arr[2]) ? $arr[2] : '';
			}
		}


		$metas = array();
		if (is_singular() && ! is_front_page()){
			$post = get_queried_object();
			$meta = $this->get_post_meta($post->ID);
			$author = new \WP_User($post->post_author);

			$default_desc = trim(strip_tags($post->post_excerpt));
			if (empty($default_desc)){
				$default_desc = trim(strip_tags($post->post_content));
			}
			$default_desc = substr($default_desc, 0, 160);

			$metas['description'] = $this->clean_string($meta->meta_description, $default_desc);
			$ogs['og:description'] = $this->clean_string($meta->og_description, $default_desc);
			$metas['author'] = $this->clean_string($author->get('display_name'));


			if (has_post_thumbnail($post->ID)){
				$id = get_post_thumbnail_id($post->ID);
				$arr = wp_get_attachment_image_src($id, self::OG_IMAGE_SIZE_ID);
				$ogs['og:image'] = isset($arr[0]) ? $arr[0] : '';
				$ogs['og:image:width'] = isset($arr[1]) ? $arr[1] : '';
				$ogs['og:image:height'] = isset($arr[2]) ? $arr[2] : '';
			}
			$ogs['og:type'] = 'article';
			$ogs['og:url'] = get_permalink($post->ID);

		} elseif(is_home() || is_front_page()){
			$default_desc = get_bloginfo('description');
			$metas['description'] = $this->clean_string($options->default_site_meta_description, $default_desc);
			$ogs['og:description'] = $this->clean_string($options->default_site_og_description, $default_desc);
			$ogs['og:type'] = 'website';
		} elseif(is_tax()){
			$tax = get_queried_object();
			$default_desc = get_bloginfo('description');

		}

		/**
		 * Echo out the metas
		 */
		if (count($metas)){
			echo PHP_EOL;
			foreach($metas as $name => $value){
				if (! empty($value)){
					printf(
						'<meta name="%s" content="%s">',
						$name,
						$value
					);
					echo PHP_EOL;
				}
			}
		}

		/**
		 * Echo out the ogs (note the property attr)
		 */
		if (count($ogs)){
			foreach($ogs as $name => $value){
				if (! empty($value)){
					printf(
						'<meta property="%s" content="%s">',
						$name,
						$value
					);
					echo PHP_EOL;
				}
			}
			echo PHP_EOL;
		}
	}

	/**
	 * @return SiteMetaSettings
	 */
	public function get_option_site_meta(){
		$o = get_option(self::OK_SITE_META);
		if (! $o instanceof SiteMetaSettings){
			$o = new SiteMetaSettings;
		}
		return $o;
	}

	/**
	 * @param $arr
	 */
	public function set_option_site_meta($arr){
		$o = $this->get_option_site_meta();
		$o->init_from_user_data($arr);
		update_option(self::OK_SITE_META, $o);
	}

	/**
	 * @param $post_id
	 * @return PostMetaData
	 */
	public function get_post_meta($post_id){
		$o = get_post_meta($post_id, self::PMK_META_TAGS, true);
		if (! $o instanceof PostMetaData){
			$o = new PostMetaData;
		}
		return $o;
	}

	/**
	 * @param int $post_id
	 * @param array $arr
	 */
	public function set_post_meta($post_id, $arr){
		$o = new PostMetaData($arr);
		update_post_meta($post_id, self::PMK_META_TAGS, $o);

	}
} 