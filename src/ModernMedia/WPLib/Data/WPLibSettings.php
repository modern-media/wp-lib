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
	public $component_enabled_metatags = false;
	public $component_enabled_socialsharing = false;


	public $facebook_app_id = '';
} 