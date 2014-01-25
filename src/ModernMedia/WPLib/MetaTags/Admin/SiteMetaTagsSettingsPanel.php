<?php
namespace ModernMedia\WPLib\MetaTags\Admin;
use ModernMedia\WPLib\Admin\BaseAdminElement;
use ModernMedia\WPLib\AjaxQuery;
use ModernMedia\WPLib\Constants;
use ModernMedia\WPLib\MetaTags\Data\SiteMetaSettings;
use ModernMedia\WPLib\MetaTags\MetaTags;
use ModernMedia\WPLib\Scripts;
use ModernMedia\WPLib\Utils;

/**
 * Class SiteMetaTagsSettingsPanel
 * @package ModernMedia\WPLib\MetaTags\Admin
 *
 * This is an admin panel for editing the site wide
 * meta tags settings. It's instantiated
 * by the MetaTags class.
 *
 */
class SiteMetaTagsSettingsPanel extends BaseAdminElement {

	/**
	 * Constructor
	 */
	public function __construct(){
		AjaxQuery::inst();
		$args = array(
			'cap' => Constants::USER_ROLE_ADMINISTRATOR,
			'title' => 'Meta Tag Settings',
			'type' => self::TYPE_PANEL,
		);

		parent::__construct($args);
	}

	/**
	 * @param null $post_id
	 */
	protected function html($post_id = null){
		if (! $this->form_data instanceof SiteMetaSettings){
			$this->form_data = MetaTags::inst()->get_option_site_meta();
		}
		require Utils::get_lib_path('includes/admin/panel/site_meta_options.php');
	}

	/**
	 * @param null $post_id
	 */
	protected function on_save($post_id = null){
		MetaTags::inst()->set_option_site_meta($_POST);
	}

	protected function on_admin_enqueue_scripts(){
		wp_enqueue_media();
		$s = Scripts::inst();
		$s->enqueue(Scripts::UPLOADER);
		$s->enqueue(Scripts::CHAR_COUNT);
	}

} 