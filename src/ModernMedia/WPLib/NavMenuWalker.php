<?php
namespace ModernMedia\WPLib;
class NavMenuWalker extends \Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker::start_lvl()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"dropdown-menu\">\n";
	}


	/**
	 * Start the element output.
	 *
	 * @see Walker::start_el()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		//Debugger::inst()->add('item', $item);
		//Debugger::inst()->add('args', $args);
		$li_attrs = array(
			'class' => array()
		);
		$a_attrs = array(
			'href' => ! empty( $item->url ) ? $item->url : '#',
			'title' => empty( $item->attr_title ) ? $item->attr_title : '',
			'class' => empty( $item->classes ) ? array() : (array) $item->classes,
			'target' => ! empty( $item->target )     ? $item->target     : '',
			'rel' => ! empty( $item->xfn )        ? $item->xfn        : ''
		);
		if (in_array('current-menu-item', $item->classes)){
			$li_attrs['class'][] = 'active';
		}
		$li_attrs['class'] = implode(' ', $li_attrs['class']);
		if (in_array('menu-item-has-children', $item->classes)){
			$a_attrs['class'][] = 'dropdown-toggle';
			$a_attrs['data-toggle'] = 'dropdown';
			$li_attrs['class'] = 'dropdown';
		}
		$a_attrs['class'] = implode(' ', $a_attrs['class']);

		$attrs = array();
		foreach($li_attrs as $key => $value){
			if (! empty($value)){
				$attrs[] = sprintf(
					'%s="%s"',
					$key,
					esc_attr($value)
				);
			}
		}
		$attrs = count($attrs) ? ' ' . implode(' ', $attrs) : '';
		$output .= sprintf('<li%s>', $attrs);

		$attrs = array();
		foreach($a_attrs as $key => $value){
			if (! empty($value)){
				$attrs[] = sprintf(
					'%s="%s"',
					$key,
					esc_attr($value)
				);
			}
		}
		$attrs = count($attrs) ? ' ' . implode(' ', $attrs) : '';
		$output .= sprintf('<a%s>%s%s%s</a>', $attrs, $args->before,apply_filters( 'the_title', $item->title, $item->ID ), $args->after);


	}
} 