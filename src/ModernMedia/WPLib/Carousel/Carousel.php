<?php
namespace ModernMedia\WPLib\Carousel;
use ModernMedia\WPLib\Scripts;
use ModernMedia\WPLib\Utils;
class Carousel {

	const PT_CAROUSEL = 'mm_wp_lib_carousel';
	const PMK_ITEMS = 'mm_wp_lib_carousel_items';
	const PMK_CAROUSEL_CLASS = 'mm_wp_lib_carousel_class';
	const SHORTCODE = 'mm_wp_lib_carouse';


	/**
	 * @var Carousel
	 */
	private static $instance = null;

	/**
	 * @return Carousel
	 */
	public static function inst(){
		if (! self::$instance instanceof Carousel){
			self::$instance = new Carousel;
		}
		return self::$instance;
	}


	/**
	 * Singleton constructor...
	 */
	private function __construct(){
		add_action('plugins_loaded', array($this, '_action_plugins_loaded'));
	}

	/**
	 * add actions...
	 */
	public function _action_plugins_loaded(){
		add_action('init', array($this, '_action_init'));
		add_shortcode(self::SHORTCODE, array($this, '_shortcode_carousel'));
		add_action('widgets_init',  array($this, '_action_widgets_init'));

	}


	/**
	 * add the widget
	 */
	public function _action_widgets_init(){
		register_widget('\\ModernMedia\\WPLib\\Carousel\\CarouselWidget');
	}


	/**
	 * @param $id
	 * @param array $attrs
	 * @return string
	 */
	public function get_carousel_html($id, $attrs = array()){
		static $counter = 0;
		$counter++;
		Scripts::inst()->enqueue(Scripts::CAROUSEL_FRONT);
		if (Carousel::PT_CAROUSEL != get_post_type($id)) return '';
		$items = get_post_meta($id, self::PMK_ITEMS , true);
		if (! is_array($items) || ! count($items)) return '';

		$carousel_id = 'carousel-' . $counter;
		$items_html = $this->get_carousel_items_html($items);
		$nav_html = $this->get_carousel_nav_html($carousel_id, $items);


		$class = array('carousel slide', 'mm-wp-lib-carousel');
		$extra = get_post_meta($id, self::PMK_CAROUSEL_CLASS, true);
		if ($extra) $class[] = $extra;
		$class = implode(' ', $class);

		return sprintf(
			'
			<div id="%s" class="%s" data-interval="%s" data-pause="%s">
				<div class="carousel-inner">
					%s
				</div>
				%s
			</div>
			',
			$carousel_id,
			$class,
			isset($attrs['interval']) ? $attrs['interval'] : '5000',
			isset($attrs['pause']) ? $attrs['pause'] : 'hover',
			$items_html,
			$nav_html
		);

	}

	/**
	 * @param int $n
	 * @param array $item
	 * @return string
	 */
	private function get_carousel_item_html($n, $item){
		$a = ! empty($item['image_links_to']) ?
			sprintf(
				'<a href="%s" title="%s">',
				$item['image_links_to'],
				esc_attr($item['heading'])
			) : '';
		$img = sprintf(
			'%s<img src="%s" alt="%s">%s',
			$a,
			wp_get_attachment_url($item['image_id']),
			esc_attr($item['heading']),
			empty($a) ? '' : '</a>'
		);
		$class = array('item');
		$extra = trim($item['classes']);
		if (! empty($extra))  $class[] = $extra;
		if ($n == 0) $class[] = 'active';
		$class = implode(' ', $class);
		return sprintf(
			'
			<div class="%s">
				%s
			</div>
			',
			$class,
			$img
		);
	}

	/**
	 * @param array $items
	 * @return string
	 */
	private function get_carousel_items_html($items){
		$html = array();

		foreach ($items as $n=>$item){
			$html[] = $this->get_carousel_item_html($n, $item);
		}
		return implode(PHP_EOL, $html);
	}

	/**
	 * @param string $carousel_id
	 * @param array $items
	 * @return string
	 */
	private function get_carousel_nav_html($carousel_id, $items){
		$lis = array();
		for($n = 0; $n < count($items); $n++){
			$class = $n == 0 ? ' class="active"' : '';
			$lis[] = sprintf(
				'<li data-target="#%s" data-slide-to="%s"%s></li>',
				$carousel_id,
				$n,
				$class
			);
		}
		$html = sprintf(
			'
			<div class="nav">
				<a class="left carousel-control" href="#%s" data-slide="prev">
					<span class="icon-prev"></span>
				</a>
				<a class="right carousel-control" href="#%s" data-slide="next">
					<span class="icon-next"></span>
				</a>
				<ol class="carousel-indicators">
				%s
				</ol>
			</div>
			',
			$carousel_id,
			$carousel_id,
			implode(PHP_EOL, $lis)
		);
		return $html;
	}

	/**
	 * @param $attrs
	 * @return string
	 */
	public function _shortcode_carousel($attrs){
		$id = $attrs['id'];
		return $this->get_carousel_html($id, $attrs);
	}

	/**
	 * Register the post type
	 */
	public function _action_init(){
		$this->register_post_type();
	}

	/**
	 * register the post type
	 */
	private function register_post_type(){
		$labels = Utils::get_post_type_labels('Carousel');
		$args = array(
			'labels' => $labels,
			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => false,
			'rewrite' => false,
			'capability_type' => 'post',
			'has_archive' => false,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array( 'title' )
		);
		register_post_type(self::PT_CAROUSEL, $args);
	}
}
