<?php
namespace ModernMedia\WPLib\Data;

class WPLibSettings extends BaseData{


	public $component_enabled_awss3 = false;
	public $awss3_id = '';
	public $awss3_secret = '';
	public $awss3_bucket = '';


	public $component_enabled_shared_sidebars = false;
	public $shared_sidebars = array();

	public $component_enabled_carousel = false;




	//meta tags settings...

	/**
	 * @var bool
	 */
	public $component_enabled_metatags = false;

	/**
	 * @var string
	 */
	public $meta_tags_default_site_description = '';

	/**
	 * @var string
	 */
	public $meta_tags_og_description = '';


	/**
	 * @var int
	 */
	public $meta_tags_og_image_id = 0;

	/**
	 * @var int
	 */
	public $meta_tags_og_image_width = 1200;

	/**
	 * @var int
	 */
	public $meta_tags_og_image_height = 630;

	//Social...

	/**
	 * @var bool
	 */
	public $component_enabled_socialsharing = false;

	/**
	 * @var string
	 */
	public $facebook_app_id = '';


	/**
	 * @var bool
	 */
	public $enable_share_this = false;
	/**
	 * @var string
	 */
	public $share_this_publisher_key = '';


	//SMTP
	public $smtp_server = '';
	public $smtp_username = '';
	public $smtp_password = '';
	public $smtp_port = 587;


} 