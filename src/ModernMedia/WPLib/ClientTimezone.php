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
 * The offset is in minutes. In both javascript and PHP
 * we follow the PHP convention that locations west of GMT
 * have a negative offset
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
			if (isset($_COOKIE) && isset($_COOKIE[self::CK_TZ])){
				/**
				 * the cookie value and $this->offset are in minutes
				 */
				self::$instance->set_offset($_COOKIE[self::CK_TZ]);
			}
		}
		return self::$instance;
	}

	/**
	 * private constructor
	 */
	private function __construct(){}
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
		$o = $this->offset;
		$local->addMinutes($o);
		$hr = str_pad(floor(abs($o)/60), 2, '0', STR_PAD_LEFT);
		$mn = str_pad(abs($o) % 60, 2, '0', STR_PAD_LEFT);
		$sign = $o >= 0 ? '+' : '-';
		$plus_str = sprintf('%s%s%s', $sign, $hr, $mn);

		$d_str = sprintf(
			'%sT%s%s',
			$local->format('Y-m-d'),
			$local->format('H:i:s'),
			$plus_str
		);
		$local = @Carbon::createFromFormat(Carbon::ISO8601, $d_str, null);
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

	/**
	 * @param int|null $off
	 */
	public function set_offset($off) {
		$this->offset = $off;
	}



} 