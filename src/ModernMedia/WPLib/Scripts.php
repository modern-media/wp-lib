<?php
namespace ModernMedia\WPLib;

class Scripts {

	const UPLOADER = 'mm_wp_lib_uploader';
	const CHAR_COUNT = 'mm_wp_lib_char_count';
	const CLIENT_TIMEZONE = 'mm_wp_lib_client_timezone';
	const CAROUSEL_FRONT = 'mm_wp_lib_carousel_front';
	const POST_PICKER = 'mm_wp_lib_post_picker';
	const TERM_PICKER = 'mm_wp_lib_term_picker';
	const WIDGET_GENERAL = 'mm_wp_lib_widget_general';
	const WIDGET_SINGLE_POST = 'mm_wp_lib_widget_single_post';
	const WIDGET_SINGLE_LINK = 'mm_wp_lib_widget_single_link';
	const WIDGET_CAROUSEL = 'mm_wp_lib_widget_carousel';
	const SOCIAL_SHARING_ASYNC = 'mm_wp_lib_social_sharing_async';
	const LINKED_IN = 'mm_wp_linked_in';
	const ATTRIBUTE_CONTROL = 'mm_wp_lib_attribute_control';

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
			self::ATTRIBUTE_CONTROL,
			array(
				'uri' => Utils::get_lib_uri('assets/js/attribute-control.js'),
				'dependencies' => array('jquery', 'jquery-ui-autocomplete'),
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
		$this->add_script(
			self::WIDGET_GENERAL,
			array(
				'uri' => Utils::get_lib_uri('assets/js/admin/widget/general.js'),
				'dependencies' => array('jquery'),
			)
		);

		$this->add_script(
			self::WIDGET_SINGLE_POST,
			array(
				'uri' => Utils::get_lib_uri('assets/js/admin/widget/single-post.js'),
				'dependencies' => array('jquery'),
			)
		);
		$this->add_script(
			self::WIDGET_CAROUSEL,
			array(
				'uri' => Utils::get_lib_uri('assets/js/admin/widget/carousel.js'),
				'dependencies' => array('jquery'),
			)
		);
		$this->add_script(
			self::SOCIAL_SHARING_ASYNC,
			array(
				'uri' => Utils::get_lib_uri('assets/js/social-sharing.js')
			)
		);

		$this->add_script(
			self::TERM_PICKER,
			array(
				'uri' => Utils::get_lib_uri('assets/js/term-picker.js'),
				'dependencies' => array('jquery', 'underscore', 'jquery-ui-autocomplete'),
			)
		);

		$this->add_script(
			self::WIDGET_SINGLE_LINK,
			array(
				'uri' => Utils::get_lib_uri('assets/js/admin/widget/single-link.js'),
				'dependencies' => array('jquery'),
			)
		);

		$this->add_script(
			self::LINKED_IN,
			array(
				'uri' => 'http://platform.linkedin.com/in.js'
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

		switch($id){
			case self::TERM_PICKER:
				$data = array();
				$taxonomies = get_taxonomies(array('public' => true), 'objects');

				foreach($taxonomies as $key => $o){
					$taxonomies[$key] = array(
						'taxonomy' => $o,
						'terms' => get_terms($key)
					);
				}
				$data['taxonomies'] = $taxonomies;

				wp_localize_script($id, 'mm_wp_lib_term_picker_data', $data);
				break;


		}
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