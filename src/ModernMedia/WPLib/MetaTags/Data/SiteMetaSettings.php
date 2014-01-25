<?php
namespace ModernMedia\WPLib\MetaTags\Data;
use ModernMedia\WPLib\Data\BaseData;
use ModernMedia\WPLib\MetaTags\MetaTags;

class SiteMetaSettings extends BaseData {

	/**
	 * @var string
	 */
	public $default_site_meta_description = '';

	/**
	 * @var string
	 */
	public $default_site_og_description = '';


	/**
	 * @var int
	 */
	public $default_site_og_image_id = 0;

	/**
	 * @var int
	 */
	public $og_image_width = 1200;

	/**
	 * @var int
	 */
	public $og_image_height = 630;


	public function init_from_user_data($arr = null,  $whitelist = array(), $blacklist = array()){
		parent::init_from_user_data($arr, $whitelist, $blacklist);
		$this->normalize();
	}

	public function init_from_object($o){
		parent::init_from_object($o);
		$this->normalize();
	}

	protected function normalize(){
		if (empty($this->og_image_width) || ! is_numeric($this->og_image_width)){
			$this->og_image_width = MetaTags::OG_IMAGE_WIDTH;
		}
		$this->og_image_width = intval($this->og_image_width);
		if (empty($this->og_image_height) || ! is_numeric($this->og_image_height)){
			$this->og_image_height = MetaTags::OG_IMAGE_HEIGHT;
		}
		$this->og_image_height = intval($this->og_image_height);
	}


} 