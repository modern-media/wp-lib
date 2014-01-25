<?php
namespace ModernMedia\WPLib;

use ModernMedia\WPLib\Data\AjaxResponse;

class AjaxQuery {

	const ACTION_POSTS_QUERY = 'mm_posts_query';
	const ACTION_IMAGE_SRC_QUERY = 'mm_image_src_query';

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
} 