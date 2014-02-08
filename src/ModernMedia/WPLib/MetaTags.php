<?php
namespace ModernMedia\WPLib;

use ModernMedia\WPLib\Admin\MetaBox\MetaTagsMetaBox;
use ModernMedia\WPLib\Data\PostMetaTagsData;

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

	/**
	 * Constructor
	 */
	private function __construct(){
		new MetaTagsMetaBox();
		add_action('plugins_loaded', array($this, '_action_plugins_loaded'));
	}

	/**
	 * add actions
	 */
	public function _action_plugins_loaded(){
		add_action('wp_head', array($this, '_action_wp_head'));
		add_action('after_setup_theme', array($this, '_action_after_setup_theme'));
	}

	/**
	 *  add the og image size
	 */
	public function _action_after_setup_theme(){
		$o = WPLib::inst()->get_settings();
		add_image_size(self::OG_IMAGE_SIZE_ID, $o->meta_tags_og_image_width, $o->meta_tags_og_image_height, false);
	}

	/**
	 * @param string $str
	 * @param string $default
	 * @return string
	 */
	public function clean_string($str = '', $default = ''){
		$str = trim(strip_tags($str));
		$str = preg_replace('/\W+/', ' ', $str);
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
		$options = WPLib::inst()->get_settings();
		$ogs = array(
			'og:site_name' => $this->clean_string(get_bloginfo('name'))
		);
		if ($options->meta_tags_og_image_id > 0){
			$arr = wp_get_attachment_image_src($options->meta_tags_og_image_id, self::OG_IMAGE_SIZE_ID);
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


			if ($meta->og_image_id > 0){
				$arr = wp_get_attachment_image_src($meta->og_image_id, self::OG_IMAGE_SIZE_ID);
				$ogs['og:image'] = isset($arr[0]) ? $arr[0] : '';
				$ogs['og:image:width'] = isset($arr[1]) ? $arr[1] : '';
				$ogs['og:image:height'] = isset($arr[2]) ? $arr[2] : '';
			} elseif (has_post_thumbnail($post->ID)){
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
			$metas['description'] = $this->clean_string($options->meta_tags_default_site_description, $default_desc);
			$ogs['og:description'] = $this->clean_string($options->meta_tags_og_description, $default_desc);
			$ogs['og:type'] = 'website';
		} elseif(is_tax()){
			$term = get_queried_object();
			$default_desc = get_bloginfo('description');
			$metas['description'] = $ogs['og:description'] = $this->clean_string($term->description, $default_desc);
			$ogs['og:type'] = 'website';
		} else {
			$default_desc = get_bloginfo('description');
			$metas['description'] = $this->clean_string($options->meta_tags_default_site_description, $default_desc);
			$ogs['og:description'] = $this->clean_string($options->meta_tags_og_description, $default_desc);
			$ogs['og:type'] = 'website';
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
	 * @param $post_id
	 * @return PostMetaTagsData
	 */
	public function get_post_meta($post_id){
		$o = get_post_meta($post_id, self::PMK_META_TAGS, true);
		if (! $o instanceof PostMetaTagsData){
			$o = new PostMetaTagsData;
		}
		return $o;
	}

	/**
	 * @param int $post_id
	 * @param array $arr
	 */
	public function set_post_meta($post_id, $arr){
		$o = new PostMetaTagsData($arr);
		update_post_meta($post_id, self::PMK_META_TAGS, $o);

	}
} 