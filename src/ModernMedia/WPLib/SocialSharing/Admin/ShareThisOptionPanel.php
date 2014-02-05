<?php
namespace ModernMedia\WPLib\SocialSharing\Admin;
use ModernMedia\WPLib\Admin\BaseAdminElement;
use ModernMedia\WPLib\Constants;
use ModernMedia\WPLib\Utils;
use ModernMedia\WPLib\SocialSharing\ShareThis;

class ShareThisOptionPanel extends BaseAdminElement{

	public function __construct(){
		$args = array(
			'cap' => Constants::USER_ROLE_ADMINISTRATOR,
			'title' => 'ShareThis Options',
			'type' => self::TYPE_PANEL,
		);

		parent::__construct($args);
	}

	public function html($post_id = null){
		require Utils::get_lib_path('includes/admin/panel/share_this_settings.php');
	}

	public function on_save($post_id = null){
		ShareThis::inst()->set_options($_POST);
	}
}