<?php
namespace ModernMedia\WPLib\AWSS3\Admin\Panel;
use ModernMedia\WPLib\AWSS3\AWSS3;
use ModernMedia\WPLib\Admin\BaseAdminElement;
use ModernMedia\WPLib\Constants;
use ModernMedia\WPLib\Utils;

class SettingsPanel extends BaseAdminElement {
	public function __construct(){
		$init = array(
			'type' => self::TYPE_PANEL,
			'cap' => Constants::USER_ROLE_ADMINISTRATOR,
			'title' => __('AWS S3 Settings'),
		);
		parent::__construct($init);
	}

	protected function html($post_id = null){
		require Utils::get_lib_path('includes/admin/panel/aws_s3_settings_panel.php');
	}

	protected function on_save($post_id = null){
		AWSS3::inst()->set_option_aws($_POST);
		$this->message = __('Settings saved!');
	}
} 