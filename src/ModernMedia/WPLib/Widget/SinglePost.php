<?php
namespace ModernMedia\WPLib\Widget;
use ModernMedia\WPLib\Scripts;
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
			'display_title' => true,
			'title' => '',
			'image_display' => 'none',
			'image_size' => 'thumbnail',
			'image_placement' => 'above_title',
			'custom_image_id' => 0,
			'excerpt' => '',
			'include_read_button' => false,
			'read_button_text' => __('Read...')
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

		$title = $instance['title'];
		if (empty($title)){
			$title = get_the_title($post->ID);
		}

		$permalink = get_permalink($post->ID);


		if ($instance['display_title']){
			$title_div = sprintf(
				'
				%s
					<a href="%s">%s</a>
				%s
				',
				$args['before_title'],
				$permalink,
				$title,
				$args['after_title']
			);
		} else {
			$title_div = '';
		}


		$img_div = false;
		switch($instance['image_display']){
			case 'featured':
				if (has_post_thumbnail($post->ID)){
					$img_div = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $instance['image_size']);
				}
				break;
			case 'custom':
				$img_div = wp_get_attachment_image_src($instance['custom_image_id'], $instance['image_size']);
				break;
		}

		if ($img_div){
			$img_div = sprintf(
				'
				<div class="image">
					<a href="%s"><img
					src="%s"
					class="img-responsive"
					title="%s"></a>
				</div>
				',
				$permalink,
				$img_div[0],
				esc_attr($title)
			);
		} else {
			$img_div = '';
		}

		if (! empty($instance['excerpt'])){
			$excerpt_div = $instance['excerpt'];
		} else {
			$excerpt_div = $post->post_excerpt;
		}
		if (empty($excerpt_div)){
			$excerpt_div = strip_tags($post->post_content);
			$excerpt_div = substr($excerpt_div, 0, 240);
			$excerpt_div .= '...';
		}

		if ($instance['include_read_button']){
			$excerpt_div .= sprintf(
				' <span class="read-more"><a href="%s">%s</a></span>',
				$permalink,
				empty($instance['read_button_text']) ? __('Read...') : $instance['read_button_text']
			);
		}
		$excerpt_div = sprintf(
			'
			<div class="excerpt">
				%s
			</div>
			',
			wpautop($excerpt_div)
		);
		switch($instance['image_placement']){
			case 'above_excerpt':
				$divs = array( $title_div, $img_div, $excerpt_div);
				break;
			case 'below_excerpt':
				$divs = array( $title_div, $excerpt_div, $img_div);
				break;
			default:
				$divs = array($img_div, $title_div, $excerpt_div);
				break;
		}


		return implode(PHP_EOL, $divs);
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





}