<?php
namespace ModernMedia\WPLib\Admin;
use ModernMedia\WPLib\Utils;
/**
 * @var $data_label
 * @var $data_preview_size
 * @var $form_name
 * @var $image_id
 */
class Controls {
	public static function attribute_control($form_name, $attributes){
		require Utils::get_lib_path('includes/admin/controls/attribute-control.php');
	}
	public static function post_picker_control($form_name, $post, $control_id){
		require Utils::get_lib_path('includes/admin/controls/post-picker-control.php');
	}
	public static function uploader_control($control_id, $form_name, $image_id, $data_label, $data_preview_size ){
		require Utils::get_lib_path('includes/admin/controls/uploader-control.php');
	}
} 