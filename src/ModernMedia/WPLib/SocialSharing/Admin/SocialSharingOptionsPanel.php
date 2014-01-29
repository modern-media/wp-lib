<?php
namespace ModernMedia\WPLib\SocialSharing\Admin;
use ModernMedia\WPLib\Admin\BaseAdminElement;
use ModernMedia\WPLib\Constants;
use ModernMedia\WPLib\Utils;

class SocialSharingOptionsPanel extends BaseAdminElement{

	public function __construct(){
		$args = array(
			'cap' => Constants::USER_ROLE_ADMINISTRATOR,
			'title' => 'Social Sharing Options',
			'type' => self::TYPE_PANEL,
		);

		parent::__construct($args);
	}

	public function html($post_id = null){
		require Utils::get_lib_path('includes/admin/panel/social_sharing_options.php');
	}

	public function on_save($post_id = null){

	}
} 