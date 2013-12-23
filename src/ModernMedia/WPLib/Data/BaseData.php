<?php
namespace ModernMedia\WPLib\Data;
use ModernMedia\WPLib\Utils;
/**
 * Class Base
 * @package MMEP\Data
 *
 * An abstract base class for our data objects
 */
abstract class BaseData {

	/**
	 * We assume that if something is passed
	 * into the constructor, then we should
	 * initialize the properties with it.
	 *
	 * We assume that if $init is an array,
	 * then it is user generated data, and
	 * needs to be stripslashed (the WordPress
	 * add slashes problem.)
	 *
	 * @param array|object|null $init
	 * @param array $whitelist
	 * @param array $blacklist
	 */
	public function __construct($init = null,  $whitelist = array(), $blacklist = array()){
		if (is_array($init)) {
			$this->init_from_user_data($init, $whitelist, $blacklist);
		} elseif (is_object($init)) {
			$this->init_from_object($init);
		}
	}

	/**
	 * @param array $arr
	 * @param array $whitelist
	 * @param array $blacklist
	 */
	public function init_from_user_data($arr, $whitelist = array(), $blacklist = array()){
		$keys = $this->get_keys();
		$keys = Utils::whitelist_blacklist_array($keys, $whitelist, $blacklist);
		foreach ($keys as $key){
			if (isset($arr[$key])) $this->{$key} = Utils::trim_stripslashes_deep($arr[$key]);
		}
	}

	/**
	 * @param $o
	 */
	public function init_from_object($o){
		$keys = $this->get_keys();
		foreach ($keys as $key){
			if (isset($o->{$key})) $this->{$key} = $o->{$key};
		}
	}

	/**
	 * @return array
	 */
	public function get_keys(){
		return array_keys(get_class_vars(get_class($this)));
	}



}