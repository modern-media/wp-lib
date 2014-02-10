<?php
namespace ModernMedia\WPLib\Widget;
use ModernMedia\WPLib\HTML;
use ModernMedia\WPLib\Scripts;
use ModernMedia\WPLib\Utils;

/**
 * Class SingleLinkWidget
 * @package ModernMedia\WPLib\Widget
 *
 * A widget that displays a single link
 */
class SingleLinkWidget extends BaseWidget  {

	const TYPE_HOME = 'home';
	const TYPE_URL 	= 'url';
	const TYPE_POST_TYPE_ARCHIVE = 'post_type_archive';
	const TYPE_TERM_ARCHIVE = 'term_archive';
	const TYPE_POST = 'single_post';
	const TYPE_AUTHOR_ARCHIVE = 'author_archive';
	const TYPE_RSS = 'rss_feed';
	const TYPE_JAVASCRIPT_VOID = 'javascript_void';
	const TYPE_HASH = 'hash';

	public function __construct(){
		if (is_admin()){
			global $pagenow;
			if ('widgets.php' == $pagenow ){
				$s = Scripts::inst();

				$s->enqueue(Scripts::WIDGET_GENERAL);
				$s->enqueue(Scripts::UPLOADER);
				$s->enqueue(Scripts::TERM_PICKER);
				$s->enqueue(Scripts::POST_PICKER);
				$s->enqueue(Scripts::WIDGET_SINGLE_LINK);
				$s->enqueue(Scripts::ATTRIBUTE_CONTROL);
				wp_enqueue_media();
			}
		}
		parent::__construct();
	}
	/**
	 * @return array
	 */
	public function get_instance_defaults() {
		return array(
			'type' => '',
			'id' => 0,
			'link_attributes' => array(),
			'url' => '',
			'post_type' => '',
			'term_id' => '',
			'taxonomy' => '',
			'post_id' => '',
			'author_id' => '',
			'hash_id' => '',
			'use_image' => false,
			'image_size' => 'medium',
			'image_id' => '',
			'image_attributes' => array(),
			'title' => '',
			'title_attribute' => '',
		);
	}

	/**
	 * @param $instance
	 * @param $reason
	 * @return bool
	 */
	public function is_widget_displayed($instance, &$reason) {
		return true;
	}
	
	public function get_instance_title($instance, $title_attribute = false){
		$value = '';
		if ($title_attribute){
			if(! empty($instance['title_attribute'])){
				$value = $instance['title_attribute'];
			} else {
				$attrs = is_array($instance['link_attributes']) ? $instance['link_attributes'] : array();
				if (isset($attrs['title']) && ! empty($attrs['title'])){
					$value = $attrs['title'];
				} elseif (! empty($instance['title'])){
					$value = $instance['title'];
				}
			}
		} elseif (! empty($instance['title'])){
			$value = $instance['title'];
		}
	
		if (! empty($value)){
			return $value;
		}
		$value = '';
		
		switch($instance['type']){
			case self::TYPE_HOME:
				$value = __('Home');
				break;
			case self::TYPE_URL:
				$value = __('Missing Link Text');
				break;
			case self::TYPE_POST_TYPE_ARCHIVE:
				$o = get_post_type_object($instance['post_type']);
				if ($o){
					$value = $o->labels->name;
				}
				break;
			case self::TYPE_TERM_ARCHIVE:
				$o = get_term($instance['term_id'], $instance['taxonomy']);
				if (! is_wp_error($o)){
					$value = $o->name;
				}

				break;
			case self::TYPE_POST:
				$value = get_the_title($instance['post_id']);
				break;
			case self::TYPE_AUTHOR_ARCHIVE:
				$user = new \WP_User($instance['author_id']);
				if ($user instanceof \WP_User && $user->ID > 0){
					$value = $user->get('display_name');
				}
				break;
			case self::TYPE_RSS:
				$value = 'RSS Feed';
				break;
			case self::TYPE_JAVASCRIPT_VOID:
				$value = __('Missing Link Text');
				break;
			case self::TYPE_HASH:
				$value = __('Missing Link Text');
				break;

		}
		return $value;
	}

	public function get_href($instance){
		$href = false;
		switch($instance['type']){
			case self::TYPE_HOME:
				$href = get_bloginfo('url');
				break;
			case self::TYPE_URL:
				$href = ! empty($instance['url']) ? $instance['url'] : $href;
				break;
			case self::TYPE_POST_TYPE_ARCHIVE:
				$href = get_post_type_archive_link($instance['post_type']);
				break;
			case self::TYPE_TERM_ARCHIVE:
				$href = get_term_link($instance['term_id'], $instance['taxonomy']);
				if (is_wp_error($href)){
					$href = false;
				}
				break;
			case self::TYPE_POST:
				$href = get_permalink($instance['post_id']);
				break;
			case self::TYPE_AUTHOR_ARCHIVE:
				$href = get_author_posts_url($instance['author_id']);
				break;
			case self::TYPE_RSS:
				$href = get_bloginfo('rss_url');
				break;
			case self::TYPE_JAVASCRIPT_VOID:
				$href = 'javascript:void(0);';
				break;
			case self::TYPE_HASH:
				$href = '#' . trim(trim($instance['hash_id']), '#');
				break;

		}
		return $href;
	}


	/**
	 * @param $args
	 * @param $instance
	 * @return string
	 */
	public function get_widget_content($args, $instance) {
		$href = $this->get_href($instance);
		if (! is_string($href)) return '';
		$attrs = is_array($instance['link_attributes']) ? $instance['link_attributes'] : array();
		$attrs = $this->attribute_field_to_keyed_array($attrs);
		$attrs['href'] = $href;
		$attrs['title'] = $this->get_instance_title($instance, true);
		$html = sprintf('<a %s>',  HTML::attr_array_to_string($attrs));
		if ($instance['use_image']){
			$image = wp_get_attachment_image_src($instance['image_id'], $instance['image_size']);
			if ($image){
				$attrs = is_array($instance['image_attributes']) ? $instance['image_attributes'] : array();
				$attrs = $this->attribute_field_to_keyed_array($attrs);
				$attrs['src'] = $image[0];
				$html .= sprintf('<img %s>',  HTML::attr_array_to_string($attrs));
			}
		}
		$html .= sprintf('<span>%s</span></a>', $this->get_instance_title($instance));
		return $html;

	}



	/**
	 * @param $instance
	 * @return void
	 */
	public function print_form_fields($instance) {
		require Utils::get_lib_path('includes/admin/widget/single_link_form.php');
	}

	/**
	 * @return array
	 */
	public function get_type_options(){
		return array(
			self::TYPE_HOME => 'Home Page',
			self::TYPE_URL 	=> 'Outside URL',
			self::TYPE_POST_TYPE_ARCHIVE => 'Post Type Archive',
			self::TYPE_TERM_ARCHIVE => 'Term (Category, Tag, etc) Archive',
			self::TYPE_POST => 'Single Post, Page, or Custom Post Type',
			self::TYPE_AUTHOR_ARCHIVE => 'Author Archive',
			self::TYPE_RSS => 'Site RSS Feed',
			self::TYPE_JAVASCRIPT_VOID => 'javascript:void(0);',
			self::TYPE_HASH => '#DOM-id',
		);
	}

	/**
	 * @param $instance
	 * @return void
	 */
	public function validate(&$instance) {
		$new_title = '';
		switch($instance['type']){
			case self::TYPE_HOME:
				$new_title = 'Home';
				break;
			case self::TYPE_URL:
				$new_title = $instance['url'];
				break;
			case self::TYPE_POST_TYPE_ARCHIVE:
				$type = get_post_type_object($instance['post_type']);
				if ($type){
					$new_title = $type->labels->name;
				}
				break;
			case self::TYPE_TERM_ARCHIVE:
				$term = get_term($instance['term_id'], $instance['taxonomy']);
				if ($term && ! is_wp_error($term)){
					$new_title = $term->name;
				}
				break;
			case self::TYPE_POST:
				$post = get_post($instance['post_id']);
				if ($post){
					$new_title = $post->post_title;
				}
				break;
			case self::TYPE_AUTHOR_ARCHIVE:
				$user = new \WP_User($instance['author_id']);
				if ($user && $user->ID > 0){
					$new_title = get_the_author_meta('display_name', $instance['author_id']);
				}
				break;
			case self::TYPE_RSS:
				$new_title = 'RSS';
				break;
			case self::TYPE_JAVASCRIPT_VOID:
			case self::TYPE_HASH:
				$new_title = 'Link Text Here';
				break;

		}
		if (empty($instance['title'])){
			$instance['title'] = $new_title;
		}
		if (empty($instance['title_attribute'])){
			$instance['title_attribute'] = $new_title;
		}

		if (! is_array($instance['link_attributes'])){
			$instance['link_attributes'] = array();
		}

		if (! is_array($instance['image_attributes'])){
			$instance['image_attributes'] = array();
		}
	}


	/**
	 * @return array
	 */
	public function get_control_options(){
		return array('width' => 400);
	}


}

