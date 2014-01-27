<?php
namespace ModernMedia\WPLib;

class Scripts {

	const UPLOADER = 'mm_wp_lib_uploader';
	const CHAR_COUNT = 'mm_wp_lib_char_count';
	const CLIENT_TIMEZONE = 'mm_wp_lib_client_timezone';
	const CAROUSEL_FRONT = 'mm_wp_lib_carousel_front';
	const POST_PICKER = 'mm_wp_lib_post_picker';

	private $scripts = array();

	/**
	 * @var Scripts
	 */
	private static $instance = null;

	/**
	 * @return Scripts
	 */
	public static function inst(){
		if (! self::$instance instanceof Scripts){
			self::$instance = new Scripts;
		}
		return self::$instance;
	}

	private function __construct(){
		$this->scripts = array();
		$this->add_script(
			self::UPLOADER,
			array(
				'uri' => Utils::get_lib_uri('assets/js/uploader.js'),
				'dependencies' => array('jquery'),
			)
		);
		$this->add_script(
			self::CHAR_COUNT,
			array(
				'uri' => Utils::get_lib_uri('assets/js/char-count.js'),
				'dependencies' => array('jquery'),
			)
		);
		$this->add_script(
			self::CLIENT_TIMEZONE,
			array(
				'uri' => Utils::get_lib_uri('assets/js/client-timezone.js'),
				'dependencies' => array('jquery'),
			)
		);

		$this->add_script(
			self::CAROUSEL_FRONT,
			array(
				'uri' => Utils::get_lib_uri('assets/js/carousel-front.js'),
				'dependencies' => array('jquery'),
			)
		);

		$this->add_script(
			self::POST_PICKER,
			array(
				'uri' => Utils::get_lib_uri('assets/js/post-picker.js'),
				'dependencies' => array('jquery', 'underscore'),
			)
		);

	}

	public function enqueue($id){
		if (! isset($this->scripts[$id])){
			return;
		}
		$script = $this->scripts[$id];

		wp_enqueue_script(
			$id,
			$script['uri'],
			$script['dependencies'],
			$script['version'],
			$script['in_footer']
		);
	}

	public function add_script($id, $arr){
		$defaults = array(
			'dependencies' => array(),
			'version' => false,
			'in_footer' => true
		);
		$arr = array_merge($defaults, $arr);
		$this->scripts[$id] = $arr;
	}
} 