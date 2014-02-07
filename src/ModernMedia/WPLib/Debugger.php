<?php
namespace ModernMedia\WPLib;
use Carbon\Carbon;
use ModernMedia\WPLib\Admin\Panel\DebuggerPanel;

class Debugger {

	const OK_DEBUGGER_DATA = 'mm-wp-lib-debugger-data';
	const KEEP_RECORDS = 5;

	/**
	 * @var Debugger
	 */
	private static $instance = null;
	private $request_data = false;

	/**
	 * @return Debugger
	 */
	public static function inst(){
		if (! self::$instance instanceof Debugger){
			self::$instance = new Debugger;
		}
		return self::$instance;
	}

	private function __construct(){
		if (! Utils::is_wp_debug()) return;
		$this->request_started = Carbon::now('UTC');
		if (is_admin()){
			new DebuggerPanel;
		}
		add_action('shutdown' , array($this, '_action_shutdown'));
	}

	/**
	 * @return array
	 */
	public function get_data(){
		if (! Utils::is_wp_debug()) return array();
		if (is_multisite()){
			$json = get_site_option(self::OK_DEBUGGER_DATA, '', false);
		} else {
			$json = get_option(self::OK_DEBUGGER_DATA, '');
		}
		$arr = json_decode($json);
		if (! is_array($arr)){
			$arr = array();
		}
		return $arr;

	}


	public function add($data, $label = ''){
		if (! Utils::is_wp_debug()) return;
		if (! is_array($this->request_data)){
			$this->request_data = array();
		}
		$d = Carbon::now('UTC');

		$r_data = array(
			'label' => empty($label) ? 'Unlabelled Debug at ' . $d->format('r') : $label,
			'timestamp' => $d->getTimestamp(),
			'data' => serialize($data)
		);
		array_unshift($this->request_data, $r_data);
	}

	public function _action_shutdown(){
		if (! Utils::is_wp_debug()) return;
		if (! is_array($this->request_data)) return;
		$data = $this->get_data();
		$d = Carbon::now('UTC');
		$r_data = array(
			'request_started' => $this->request_started->getTimestamp(),
			'request_ended' => $d->getTimestamp(),
			'data' => $this->request_data
		);
		array_unshift($data, $r_data);
		$data = array_slice($data, 0, self::KEEP_RECORDS);
		$value = json_encode($data);
		if (is_multisite()){
			update_site_option(self::OK_DEBUGGER_DATA, $value);
		} else {
			update_option(self::OK_DEBUGGER_DATA, $value);
		}
	}




} 