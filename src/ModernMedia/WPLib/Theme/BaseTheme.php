<?php
namespace ModernMedia\WPLib\Theme;

abstract class BaseTheme {

	protected function __construct(){
		add_action( 'after_setup_theme', array($this, '_action_after_setup_theme') );
		add_filter( 'wp_title', array($this, '_filter_wp_title'), 10, 2 );
	}
	public function _action_after_setup_theme(){

		$parent_setup = array();
		$child_setup = array();
		if (is_child_theme()){
			$path = get_template_directory() . '/settings/setup.php';
			if (file_exists($path)){
				$parent_setup = require($path);
			}
		}
		$path = get_stylesheet_directory() . '/settings/setup.php';
		if (file_exists($path)){
			$child_setup = require($path);
		}

		/**
		 * Sidebars...
		 */
		$els = array_merge(
			isset($parent_setup['sidebars']) ? $parent_setup['sidebars'] : array(),
			isset($child_setup['sidebars']) ? $child_setup['sidebars'] : array()
		);

		$defaults = array(
			'name' => 'Untitled Sidebar',
			'before_widget' => '<li id="%1$s" class="widget %2$s"><div class="widget-inner">',
			'after_widget' => '</div></div></li>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3><div class="widgetcontent">',
		);
		foreach ($els as $id => $el) {
			$el['id'] = $id;
			$el = array_merge( $defaults, $el);
			register_sidebar($el);
		}

		/**
		 * Nav Menus...
		 */
		$els = array_merge(
			isset($parent_setup['menus']) ? $parent_setup['menus'] : array(),
			isset($child_setup['menus']) ? $child_setup['menus'] : array()
		);
		foreach ($els as $id => $el) {
			register_nav_menu($id, $el);
		}

		/**
		 * Image sizes...
		 */
		$els = array_merge(
			isset($parent_setup['image-sizes']) ? $parent_setup['image-sizes'] : array(),
			isset($child_setup['image-sizes']) ? $child_setup['image-sizes'] : array()
		);
		foreach ($els as $id => $el) {
			add_image_size(
				$id,
				$el['width'],
				$el['height'],
				isset($el['crop']) && true == $el['crop']
			);
		}

		/**
		 * Supports...
		 */

		$els = array_merge(
			isset($parent_setup['supports']) ? $parent_setup['supports'] : array(),
			isset($child_setup['supports']) ? $child_setup['supports'] : array()
		);
		foreach($els as $el){
			add_theme_support($el);
		}

	}

	public function _filter_wp_title( $title, $sep ) {
		global $paged, $page;

		if ( is_feed() )
			return $title;

		// Add the site name.
		$title .= get_bloginfo( 'name' );

		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title = "$title $sep $site_description";

		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 )
			$title = "$title $sep " . sprintf( __( 'Page %s' ), max( $paged, $page ) );

		return $title;
	}

	/**
	 * @param $id
	 * @param string $enclosing_tag
	 * @param string $enclosing_class
	 * @param string $enclosing_id
	 */
	public function display_sidebar($id, $enclosing_tag='', $enclosing_class = '', $enclosing_id = ''){
		ob_start();
		dynamic_sidebar($id);
		$sidebar = ob_get_clean();
		if (empty($sidebar)) return;
		if (! empty($enclosing_tag)){
			printf(
				'<%s%s%s>%s</%s>',
				$enclosing_tag,
				empty($enclosing_class) ? '' : sprintf(' class="%s"', $enclosing_class),
				empty($enclosing_id) ? '' : sprintf(' id="%s"', $enclosing_id),
				$sidebar
			);
		} else {
			echo $sidebar;
		}
	}


} 