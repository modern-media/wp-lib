<?php
namespace ModernMedia\MustUse\Widget;
use ModernMedia\WPLib\Utils;
use ModernMedia\MustUse\SocialSharing\SocialSharing;
use ModernMedia\WPLib\Widget\BaseWidget;

/**
 * Class ModernMediaSinglePost
 * @package ModernMedia\MustUse\Widget
 *
 * A widget that displays a single post
 */
class SinglePost extends BaseWidget{

	/**
	 * @return array
	 */
	protected function get_instance_defaults() {
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
			'thumbnail_size' => 'thumbnail',
			'alternate_title' => '',
			'alternate_excerpt' => '',
			'alternate_image' => '',
			'tag_post_as' => '',
			'include_read_button' => false,
			'read_button_text' => 'Read',
			'include_social' => false
		);
	}

	public function get_post_type(){
		return 'any';
	}

	/**
	 * @param $instance
	 * @param $reason_not_displayed
	 * @return bool
	 */
	protected function is_widget_displayed($instance, &$reason_not_displayed) {
		return true;
	}

	/**
	 * @param $instance
	 * @return bool
	 */
	protected function is_widget_content_displayed($instance) {
		return true;
	}

	/**
	 * @param $instance
	 * @return string
	 */
	protected function get_widget_content($instance) {
		$post_id = $instance['post_id'];
		$post = get_post($post_id);
		if (! $post) return '';
		$url = get_permalink($post_id);
		$html = '';
		$alternate_image_id = trim($instance['alternate_image']);
		$title = get_the_title($post_id);
		$title_esc = esc_attr($title);

		if (! empty($alternate_image_id)){
			list($src) = wp_get_attachment_image_src($alternate_image_id, $instance['thumbnail_size']);
			$html .=  sprintf(
				'<div class="featured-image">
					<a href="%s" title="%s"><img src="%s" class="img-responsive" alt="%s"></a>
				</div>',
				$url,
				$title_esc,
				$src,
				$title_esc
			);
		} elseif ( has_post_thumbnail($post_id) ){
			$id = get_post_thumbnail_id($post_id);
			list($src) = wp_get_attachment_image_src($id, $instance['thumbnail_size']);
			$html .=  sprintf(
				'<div class="featured-image">
					<a href="%s" title="%s"><img src="%s" class="img-responsive" alt="%s"></a>
				</div>',
				$url,
				$title_esc,
				$src,
				$title_esc
			);
		}
		if (! empty($instance['tag_post_as'])){
			$html .= sprintf(
				'<div class="widget-tag">%s</div>',
				$instance['tag_post_as']
			);
		}
		$html .= sprintf(
			'<h3><a href="%s" title="%s">%s</a></h3>',
			$url,
			$title_esc,
			$instance['alternate_title'] == '' ? $title : $instance['alternate_title']
		);
		if (! empty($instance['alternate_excerpt'])){
			$html .= apply_filters('the_excerpt', $instance['alternate_excerpt']);
		} elseif ($post->post_excerpt) {
			$html .= apply_filters('the_excerpt', $post->post_excerpt);
		} else {
			$html .= apply_filters('the_excerpt', $post->post_content);
		}
		if ($instance['include_read_button']){
			$btn_text = $instance['read_button_text'];
			if (empty ($btn_text)) $btn_text = 'Read More';
			$html .= sprintf(
				'<p><a href="%s" class="btn btn-success btn-read-single-post">%s</a></p>',
				$url,
				$btn_text
			);
		}
		if ($instance['include_social']){
			$html .= SocialSharing::get_share_bar($post_id);
		}

		return $html;
	}



	/**
	 * @param $instance
	 * @return void
	 */
	protected function print_form_fields($instance) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$widget = &$this;
		require Utils::get_lib_path('/includes/widget/single_post_form.php');
	}

	/**
	 * @param $instance
	 * @return void
	 */
	protected function validate(&$instance) {
		$post = get_post($instance['post_id']);
		if ($post){
			$instance['title'] = $post->post_title;
		}

	}

	/**
	 * @return string
	 */
	protected function get_name() {
		return 'MM Single Post';
	}

	/**
	 * @return string
	 */
	protected function get_desc() {
		return 'Displays a single post or custom post type.';
	}

	/**
	 * @return array
	 */
	protected function get_control_options(){
		return array('width' => 400);
	}

	/**
	 * @return bool
	 */
	protected function does_widget_have_title_option() {
		return false;
	}
}