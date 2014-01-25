<?php
namespace ModernMedia\WPLib\MetaTags\Data;
use ModernMedia\WPLib\Data\BaseData;
use ModernMedia\WPLib\MetaTags\MetaTags;

class PostMetaData extends BaseData {

	/**
	 * @var string
	 */
	public $meta_description = '';

	/**
	 * @var string
	 */
	public $og_description = '';

	/**
	 * @var int
	 */
	public $og_image_id = 0;


	public function init_from_user_data($arr = null,  $whitelist = array(), $blacklist = array()){
		parent::init_from_user_data($arr, $whitelist, $blacklist);
		$this->normalize();
	}

	public function init_from_object($o){
		parent::init_from_object($o);
		$this->normalize();
	}

	protected function normalize(){
		$this->meta_description = MetaTags::inst()->clean_string($this->meta_description);
		$this->og_description = MetaTags::inst()->clean_string($this->og_description);
	}
} 