<?php
namespace ModernMedia\WPLib\Widget;
use ModernMedia\WPLib\Helper\HTML;
use ModernMedia\WPLib\Scripts;
use ModernMedia\WPLib\SocialSharing\ShareThis;
use ModernMedia\WPLib\Utils;

/**
 * Class SinglePost
 * @package ModernMedia\WPLib\Widget
 *
 * A widget that displays a single post
 */
class SinglePost extends BaseWidget{


	public function __construct(){
		if (is_admin()){
			global $pagenow;
			if ('widgets.php' == $pagenow ){
				$s = Scripts::inst();
				$s->enqueue(Scripts::POST_PICKER);
				$s->enqueue(Scripts::WIDGET_GENERAL);
				$s->enqueue(Scripts::WIDGET_SINGLE_POST);
				$s->enqueue(Scripts::UPLOADER);
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
			'id' => 0,
			'title' => '',
			'image_display' => 'featured',
			'image_size' => 'medium',
			'custom_image_id' => 0,
			'excerpt' => '',
			'include_read_button' => false,
			'read_button_text' => __('Read...'),
			'read_button_block' => false,
			'image_attributes' => array(),
			'link_image' => true,
			'image_link_attributes' => array(),
			'title_link_attributes' => array(),
			'link_title' => true,
			'read_button_attributes' => array(),
			'included_elements' => array('image', 'title', 'excerpt'),
			'include_feature_tag' => false,
			'feature_tag_text' => '',
			'include_social' => false,
		);
	}

	public function get_element_options(){
		return array(
			'title' => __('Header'),
			'image' => __('Image'),
			'excerpt' => __('Excerpt')
		);
	}

	public function get_image_display_options(){
		return array(
			'featured' => __('Use featured image'),
			'custom' => __('Use another image')
		);
	}





	public function get_image_size_options(){
		$sizes = get_intermediate_image_sizes();
		$keyed = array();
		foreach($sizes as $s){
			$keyed[$s] = $s;
		}
		return $keyed;
	}

	/**
	 * @param $instance
	 * @param $reason
	 * @return bool
	 */
	public function is_widget_displayed($instance, &$reason) {
		$post = get_post($instance['id']);
		if(! $post){
			$reason = __('No post selected.');
			return false;
		}
		return true;
	}



	/**
	 * @param $args
	 * @param $instance
	 * @return string
	 */
	public function get_widget_content($args, $instance) {

		$post = get_post($instance['id']);
		if (! $post) return '';

		$elements = array();
		$post_title = get_the_title($post->ID);
		$permalink = get_permalink($post->ID);
		$elements['title'] = $this->get_widget_title_html($args, $instance, $post_title, $permalink);
		$elements['image'] = $this->get_widget_image_html($args, $instance, $post_title, $permalink);
		$elements['excerpt'] = $this->get_widget_excerpt_html($args, $instance, $post_title, $permalink);
		$elements['social'] = $this->get_widget_social_html($args, $instance, $post_title, $permalink);
		$divs = array();
		foreach($instance['included_elements'] as $key){
			if ($elements[$key]){
				$divs[] = $elements[$key];
			}
		}

		return implode(PHP_EOL, $divs);
	}


	private function get_widget_social_html($args, $instance, $post_title, $permalink){
		if (! in_array('social', $instance['included_elements'])) {
			return false;
		}
		return '<div class="widget-social"></div>';
	}
	private function get_widget_excerpt_html($args, $instance, $post_title, $permalink){
		if (! in_array('excerpt', $instance['included_elements'])) {
			return false;
		}
		$post = get_post($instance['id']);
		if (! empty($instance['excerpt'])){
			$html = $instance['excerpt'];
		} else {
			$html = $post->post_excerpt;
		}
		if (empty($html)){
			$html = strip_tags($post->post_content);
			$html = substr($html, 0, 240);
			$html .= '...';
		}
		if ($instance['include_read_button']){
			$attrs = is_array($instance['read_button_attributes']) ? $instance['read_button_attributes'] : array();
			$attrs = $this->attribute_field_to_keyed_array($attrs);
			$attrs['href'] = $permalink;
			$btn = ! empty($instance['read_button_text']) ? $instance['read_button_text'] : __('Read...');
			if (! isset($attrs['title']) || empty($attrs['title'])){
				if (! empty($instance['title'])){
					$attrs['title'] = $instance['title'];
				} else {
					$attrs['title'] = $post_title;
				}
			}
			$btn = sprintf('<a %s>%s</a>', HTML::attr_array_to_string($attrs), $btn);
			if ($instance['read_button_block']){
				$btn = sprintf('<p class="read-btn-ctr">%s</p>', $btn);
				$html .= PHP_EOL . $btn;
			} else {
				$html .= ' ' . $btn;
			}
			if ($instance['include_social']){
				$html .= ShareThis::inst()->get_sharebar($instance['id']);
			}

		}
		return sprintf('<div class="widget-excerpt">%s</div>', wpautop($html));
	}

	private function get_widget_image_html($args, $instance, $post_title, $permalink){
		if (! in_array('image', $instance['included_elements'])) {
			return false;
		}
		$image = false;
		switch($instance['image_display']){
			case 'featured':
				if (has_post_thumbnail($instance['id'])){
					$image = wp_get_attachment_image_src(get_post_thumbnail_id($instance['id']), $instance['image_size']);
				}
				break;
			case 'custom':
				$image = wp_get_attachment_image_src($instance['custom_image_id'], $instance['image_size']);
				break;
		}
		if (! $image) {
			return false;
		}
		$attrs = is_array($instance['image_attributes']) ? $instance['image_attributes'] : array();
		$attrs = $this->attribute_field_to_keyed_array($attrs);
		$attrs['src'] = $image[0];
		$html = sprintf('<img %s>', HTML::attr_array_to_string($attrs));
		if ($instance['link_image']){
			$attrs = is_array($instance['image_link_attributes']) ? $instance['image_link_attributes'] : array();
			$attrs = $this->attribute_field_to_keyed_array($attrs);
			if (! isset($attrs['title']) || empty($attrs['title'])){
				if (! empty($instance['title'])){
					$attrs['title'] = $instance['title'];
				} else {
					$attrs['title'] = $post_title;
				}
			}
			$attrs['href'] = $permalink;
			$html = sprintf('<a %s>%s</a>', HTML::attr_array_to_string($attrs), $html);
		}
		return sprintf('<div class="widget-image">%s</div>',$html);
	}


	private function get_widget_title_html($args, $instance, $post_title, $permalink){
		if (! in_array('title', $instance['included_elements'])) {
			return false;
		}

		$html = empty($instance['title']) ? $instance['title'] : $post_title;
		if($instance['link_title']){
			$attrs = is_array($instance['title_link_attributes']) ? $instance['title_link_attributes'] : array();
			$attrs = $this->attribute_field_to_keyed_array($attrs);
			if (! isset($attrs['title']) || empty($attrs['title'])){
				if (! empty($instance['title'])){
					$attrs['title'] = $instance['title'];
				} else {
					$attrs['title'] = $post_title;
				}
			}
			$attrs['href'] = $permalink;
			$html = sprintf('<a %s>%s</a>', HTML::attr_array_to_string($attrs), $html);
		}
		$html = $args['before_title'] . $html . $args['after_title'];
		if ($instance['include_feature_tag']){
			$html = sprintf(
				'
				<div class="featured-tag">%s</div>
				%s
				',
				$instance['feature_tag_text'],
				$html
			);
		}
		return sprintf('<div class="widget-header">%s</div>',$html);


	}


	/**
	 * @param $instance
	 * @return void
	 */
	public function print_form_fields($instance) {
		require Utils::get_lib_path('includes/admin/widget/single_post_form.php');
	}


	/**
	 * @param $instance
	 * @return void
	 */
	public function validate(&$instance) {
		$post = get_post($instance['post_id']);
		if ($post){
			$instance['title'] = $post->post_title;
		}
		if (! is_array($instance['image_attributes'])){
			$instance['image_attributes'] = array();
		}
		if (! is_array($instance['image_link_attributes'])){
			$instance['image_link_attributes'] = array();
		}
		if (! is_array($instance['title_link_attributes'])){
			$instance['title_link_attributes'] = array();
		}
		if (! is_array($instance['read_button_attributes'])){
			$instance['read_button_attributes'] = array();
		}
		if (! is_array($instance['included_elements'])){
			$instance['included_elements'] = array();
		}

	}

	/**
	 * @return string
	 */
	public function get_name() {
		return 'Single Post Widget';
	}

	/**
	 * @return string
	 */
	public function get_desc() {
		return 'Displays a single post or custom post type.';
	}

	/**
	 * @return array
	 */
	public function get_control_options(){
		return array('width' => 350);
	}





}