<?php
namespace ModernMedia\WPLib\Widget;
use ModernMedia\WPLib\Scripts;
use ModernMedia\WPLib\Utils;

/**
 * Class SingleLink
 * @package ModernMedia\WPLib\Widget
 *
 * A widget that displays a single link
 */
class SingleLink extends BaseWidget  {

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
			Scripts::inst()->enqueue(Scripts::POST_PICKER);
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
			'link_classes' => '',
			'link_data_icon' => '',
			'link_extra_attributes' => '',
			'url' => '',
			'post_type' => '',
			'term_id' => '',
			'taxonomy' => '',
			'post_id' => '',
			'author_id' => '',
			'hash_id' => '',
			'link_as_image' => '',
		);
	}

	/**
	 * @param $instance
	 * @param $reason_not_displayed
	 * @return bool
	 */
	public function is_widget_displayed($instance, &$reason_not_displayed) {
		return true;
	}

	/**
	 * @param $instance
	 * @return bool
	 */
	public function is_widget_content_displayed($instance) {
		return true;
	}

	/**
	 * @param $args
	 * @param $instance
	 * @return string
	 */
	public function get_widget_content($args, $instance) {
		$url = null;
		switch($args['type']){
			case self::TYPE_HOME:
				$url = get_bloginfo('url');
				break;
			case self::TYPE_URL:
				$url = $args['url'];
				break;
			case self::TYPE_POST_TYPE_ARCHIVE:
				$url = get_post_type_archive_link($args['post_type']);
				break;
			case self::TYPE_TERM_ARCHIVE:
				$url = get_term_link($args['term_id'], $args['taxonomy']);
				break;
			case self::TYPE_POST:
				$url = get_permalink($args['post_id']);
				break;
			case self::TYPE_AUTHOR_ARCHIVE:
				$url = get_author_posts_url($args['author_id']);
				break;
			case self::TYPE_RSS:
				$url = get_bloginfo('rss_url');
				break;
			case self::TYPE_JAVASCRIPT_VOID:
				$url = 'javascript:void(0);';
				break;
			case self::TYPE_HASH:
				$url = '#' . trim(trim($args['hash_id']), '#');
				break;

		}
		if (! is_string($url)) return '';
		$link_as_image = trim($args['link_as_image']);
		if (! empty($link_as_image)){
			$inner = sprintf(
				'<img src="%s" alt="%s">',
				wp_get_attachment_url($link_as_image),
				esc_attr($args['title'])
			);
		} else {
			$inner = sprintf(
				'%s <span class="text">%s</span>',
				empty($args['link_data_icon']) ? '' : sprintf('<span data-icon="&#x%s;"></span>', dechex($args['link_data_icon'])),
				$args['title']
			);
		}

		$outer = sprintf(
			'<a href="%s"%s%s>%s</a>',
			$url,
			empty($args['link_classes']) ? '' : sprintf(' class="%s"', $args['link_classes']),
			empty($args['link_extra_attributes']) ? '' : ' ' . $args['link_extra_attributes'],
			$inner
		);
		return $outer;

	}



	/**
	 * @param $instance
	 * @return void
	 */
	public function print_form_fields($instance) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$widget = &$this;
		$path = Utils::get_lib_path('includes/admin/widget/single_link_form.php');
		require($path);

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
			self::TYPE_HASH => '#',
		);
	}

	/**
	 * @param $instance
	 * @return void
	 */
	public function validate(&$instance) {
		if (empty($instance['title'])){
			switch($instance['type']){
				case self::TYPE_HOME:
					$instance['title'] = 'Home';
					break;
				case self::TYPE_URL:
					$instance['title'] = $instance['url'];
					break;
				case self::TYPE_POST_TYPE_ARCHIVE:
					$type = get_post_type_object($instance['post_type']);
					if ($type){
						$instance['title'] = $type->labels->name;
					}
					break;
				case self::TYPE_TERM_ARCHIVE:
					$term = get_term($instance['term_id'], $instance['taxonomy']);
					if ($term && ! is_wp_error($term)){
						$instance['title'] = $term->name;
					}
					break;
				case self::TYPE_POST:
					$post = get_post($instance['post_id']);
					if ($post){
						$instance['title'] = $post->post_title;
					}
					break;
				case self::TYPE_AUTHOR_ARCHIVE:
					$user = new \WP_User($instance['author_id']);
					if ($user && $user->ID > 0){
						$instance['title'] = get_the_author_meta('display_name', $instance['author_id']);
					}
					break;
				case self::TYPE_RSS:
					$instance['title'] = 'RSS';
					break;
				case self::TYPE_JAVASCRIPT_VOID:
				case self::TYPE_HASH:
					$user = wp_get_current_user();
					$instance['title'] = sprintf('%s gives good js.', get_the_author_meta('display_name', $user->ID));
					break;

			}
		}
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return 'MM Single Link';
	}

	/**
	 * @return string
	 */
	public function get_desc() {
		return 'Displays a link to one af a variety of things.';
	}

	/**
	 * @return array
	 */
	public function get_control_options(){
		return array('width' => 400);
	}

	/**
	 * @return bool
	 */
	public function does_widget_have_title_option() {
		return false;
	}


}

