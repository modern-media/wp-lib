<?php
namespace ModernMedia\WPLib\Admin\Panel;
use ModernMedia\WPLib\Admin\BaseAdminElement;
use ModernMedia\WPLib\AjaxQuery;
use ModernMedia\WPLib\AWSS3;
use ModernMedia\WPLib\Data\WPLibSettings;
use ModernMedia\WPLib\WPLib;
use ModernMedia\WPLib\Utils;
use ModernMedia\WPLib\Scripts;

class WPLibSettingsPanel extends BaseAdminElement {


	public function __construct(){
		AjaxQuery::inst();
		$init = array(
			'title' => 'Modern Media WP Library Settings',
			'id' => 'mm-wp-lib-settings',
			'type' => self::TYPE_PANEL,
			'ajax_actions' => array('check_aws_settings'),
		);
		parent::__construct($init);
	}

	public function html($post_id = null){
		require Utils::get_lib_path('includes/admin/panel/wp-lib-settings.php');
	}

	public function on_save($post_id = null){
		WPLib::inst()->set_settings($_POST);

	}

	/**
	 * @param string $action
	 * @param \ModernMedia\WPLib\Data\AjaxResponse $response
	 */
	public function on_ajax($action, &$response){

		switch($action){
			case 'check_aws_settings':
				$p = AWSS3::inst();
				$opts = new WPLibSettings($_POST);
				if (! $p->check_settings($opts, $error)){
					$response->respond_with_error('awsS3', $error);
				} else {
					$response->respond_with_data(__('Your AWS S3 settings are valid.'));
				}
				break;

		}
		die();

	}

	/**
	 * enqueue the counter and the image uploader js
	 */
	protected function on_admin_enqueue_scripts(){
		wp_enqueue_media();
		$s = Scripts::inst();
		$s->enqueue(Scripts::UPLOADER);
		$s->enqueue(Scripts::CHAR_COUNT);
	}
} 