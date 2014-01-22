<?php
namespace ModernMedia\WPLib\Network;

use ModernMedia\WPLib\Network\Admin\Panel\DomainMapperPanel;

class DomainMapper {

	/**
	 * @var array
	 */
	private $map = null;



	public function __construct($map = null){
		if (isset($GLOBALS['modern_media_domain_map'])){
			echo '<pre>'; var_dump($GLOBALS['modern_media_domain_map']); die();
		}
		//add_action('plugins_loaded', array($this, '_action_plugins_loaded'));
	}

	public function _action_plugins_loaded(){

	}



} 