<?php
namespace ModernMedia\WPLib\Widget;
use ModernMedia\WPLib\Scripts;
use ModernMedia\WPLib\Utils;
use ModernMedia\WPLib\SocialSharing\SocialSharing;

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
			'image_display' => 'none',
			'image_size' => 'thumbnail',
			'image_placement' => 'above_title',
			'custom_image_id' => 0,
			'excerpt' => '',
			'include_read_button' => false,
			'read_button_text' => __('Read')
		);
	}

	public function get_image_display_options(){
		return array(
			'none' => __('No image'),
			'featured' => __('Use featured image'),
			'custom' => __('Use another image')
		);
	}

	public function get_image_placement_options(){
		return array(
			'above_title' => __('Above Title'),
			'above_excerpt' => __('Above Excerpt'),
			'below_excerpt' => __('Below Excerpt')
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

	public function get_post_type(){
		return 'any';
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
	 * @param $instance
	 * @return string
	 */
	public function get_widget_content($instance) {
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

	/**
	 * @return bool
	 */
	public function does_widget_have_title_option() {
		return true;
	}
	/**
	 * @return bool
	 */
	public function does_widget_have_title_link_option() {
		return false;
	}


}