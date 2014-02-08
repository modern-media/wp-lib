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

	public $component_enabled_socialsharing = false;


	public $facebook_app_id = '';


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

} 