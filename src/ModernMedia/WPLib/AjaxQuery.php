<?php
namespace ModernMedia\WPLib;

use ModernMedia\WPLib\Data\AjaxResponse;

class AjaxQuery {

	const ACTION_POSTS_QUERY = 'mm_posts_query';
	const ACTION_IMAGE_SRC_QUERY = 'mm_image_src_query';
	const ACTION_FEATURED_IMAGE_QUERY = 'mm_featured_image_query';

	/**
	 * @var AjaxQuery
	 */
	private static $instance = null;

	/**
	 * @return AjaxQuery
	 */
	public static function inst(){
		if (! self::$instance instanceof AjaxQuery){
			self::$instance = new AjaxQuery;
		}
		return self::$instance;
	}

	private function __construct(){
		$slug = 'wp_ajax_' . self::ACTION_IMAGE_SRC_QUERY;
		add_action($slug, array($this, '_action_image_src_query'));

		$slug = 'wp_ajax_' . self::ACTION_POSTS_QUERY;
		add_action($slug, array($this, '_action_posts_query'));

		$slug = 'wp_ajax_' . self::ACTION_FEATURED_IMAGE_QUERY;
		add_action($slug, array($this, '_action_featured_image_query'));
	}

	public function _action_image_src_query(){
		$response = new AjaxResponse();
		$image_id = isset($_POST['image_id']) ? $_POST['image_id'] : false;
		if (! $image_id){
			$response->respond_with_error('image_id', __('You must specify an image id.'));
		}
		if (! wp_attachment_is_image($image_id)){
			$response->respond_with_error('image_id', __('That is not an image.'));
		}
		$size = isset($_POST['size']) ? $_POST['size'] : 'thumbnail';
		$arr = wp_get_attachment_image_src($image_id, $size);
		$response->respond_with_data($arr);
	}

	public function _action_featured_image_query(){
		$response = new AjaxResponse();
		$post_id = isset($_POST['post_id']) ? $_POST['post_id'] : false;
		$data = array();
		if (! $post_id){
			$data['has_featured_image'] = false;
			$data['message'] = __('No post selected.');
			$response->respond_with_data($data);
		}
		$data['has_featured_image'] = has_post_thumbnail($post_id);
		if (! $data['has_featured_image']){
			$data['message'] = __('The post has no featured image.');
			$response->respond_with_data($data);
		}
		$size = isset($_POST['size']) ? $_POST['size'] : 'thumbnail';
		$image_id = get_post_thumbnail_id($post_id);
		$arr = wp_get_attachment_image_src($image_id, $size);
		$data['src'] = $arr[0];
		$response->respond_with_data($data);

	}

	public function _action_posts_query(){
		$response = new AjaxResponse();
		$q = array(
			'posts_per_page' => 10,
		);
		if ('any' != $_POST['post_type']);
		$q['post_type'] = $_POST['post_type'];
		if (! empty( $_POST['s'])){
			$q['s'] = $_POST['s'];
		}
		$q = new \WP_Query($q);
		$response->respond_with_data($q);

	}
} 