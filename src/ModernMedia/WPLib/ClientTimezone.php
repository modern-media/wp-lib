<?php
namespace ModernMedia\WPLib;
use Carbon\Carbon;

/**
 * Class ClientTimezone
 * @package ModernMedia\WPLib
 *
 * This class handles setting a timezone offset cookie
 * via js on the client, the retrieving that value to
 * convert UTC times to local times on the client.
 *
 * The offset is in minutes.
 *
 * Elements that use this should call
 *
 * ClientTimezone::inst()->enqueue_front()
 *
 * or
 *
 * ClientTimezone::inst()->enqueue_admin()
 * before the relevant actions.
 *
 */
class ClientTimezone {
	const CK_TZ = 'mm_wp_lib_client_timezone';
	private static $instance = null;

	/**
	 * The offset in minutes from UTC
	 *
	 * @var null|int
	 */
	private $offset = null;


	/**
	 * @return ClientTimezone
	 */
	public static function inst(){
		if (! self::$instance instanceof ClientTimezone){
			self::$instance = new ClientTimezone;
		}
		return self::$instance;
	}

	/**
	 * private constructor
	 */
	private function __construct(){
		if (isset($_COOKIE[self::CK_TZ])){
			/**
			 * the cookie value and $this->offset are in minutes
			 */
			$this->offset = $_COOKIE[self::CK_TZ];
		}


	}
	public function enqueue_front(){
		add_action('wp_enqueue_scripts', array($this, '_enqueue_scripts'));
	}

	public function enqueue_admin(){
		add_action('admin_enqueue_scripts', array($this, '_enqueue_scripts'));
	}



	/**
	 * Adds the script that sets the cookie on the client
	 */
	public function _enqueue_scripts(){
		Scripts::inst()->enqueue(Scripts::CLIENT_TIMEZONE);
	}

	/**
	 * @param Carbon $utc
	 * @return Carbon
	 */
	public function utc_to_local($utc){
		$local = $utc->copy();
		$off = $this->get_offset();
		$off = is_null($off) ? 0 : intval($off);
		$local->setTimestamp($utc->getTimestamp() - ($off * 60));
		return $local;
	}



	/**
	 * Get the offset in minutes from UTC,
	 * if the cookie has been set on the client.
	 * If not, return null.
	 * @return null|int
	 */
	public function get_offset() {
		return $this->offset;
	}



} 