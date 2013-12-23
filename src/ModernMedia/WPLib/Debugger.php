<?php
namespace ModernMedia\WPLib;

class Debugger {

	/**
	 * @var Debugger
	 */
	private static $instance = null;

	public $data = array();

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
		$this->data = array();
		add_action('wp_footer', array($this, '_action_wp_footer'), 99999);
	}

	public function add($key, $data){
		$this->data[] = array(
			'key' => $key,
			'data' => $data
		);
		\ChromePhp::info($key, $data);
	}

	public function _action_wp_footer(){
//		if (! count($this->data)) return;
//		echo '<script type="text/javascript">' . PHP_EOL;
//		echo 'if (console.log) {' . PHP_EOL;
//		echo 'var wp_dbg_data = ' . json_encode($this->data) . ';' . PHP_EOL;
//		echo 'var n;' . PHP_EOL;
//		echo 'for(n = 0; n < wp_dbg_data.length; n++){' . PHP_EOL;
//		echo 'console.log(wp_dbg_data.key, wp_dbg_data.data);' . PHP_EOL;
//		echo '}' . PHP_EOL;
//		echo '}' . PHP_EOL;
//		echo '</script>' . PHP_EOL;

	}

} 