<?php
namespace ModernMedia\WPLib;

/**
 * Class NetworkSidebarSharing
 * @package ModernMedia\WPLib
 *
 * This class synchs a particular set of sidebars between sites on a network,
 * meaning that edits made to a shared sidebar on one site will be reflected on
 * all the others. Importantly, for this to work, the sites' shared sidebars
 * must have the same ID.
 */
class NetworkSidebarSharing {


	private static $instance;

	/**
	 * @return NetworkSidebarSharing
	 */
	public static function inst(){
		if (! self::$instance instanceof NetworkSidebarSharing){
			self::$instance = new NetworkSidebarSharing;
		}
		return self::$instance;
	}

	private function __construct(){

		/**
		 * This is kind of janky, but it's the only way to do it, as
		 * there does not seem to be a dedicated hook for "this site's
		 * sidebar widgets have changed'.
		 * This should work for the following cases:
		 * (1) widgets are edited
		 * (2) widgets are moved
		 * (3) widgets are deleted.
		 */

		if (isset($_POST) && isset($_POST['savewidgets'])) {
			add_filter('wp_die_ajax_handler', array($this, '_filter_wp_die_ajax_handler'), 10, 1);
		}

	}
	public function _filter_wp_die_ajax_handler($handler){
		$this->refresh();
		return $handler;
	}

	/**
	 * Refresh all the blogs' shared sidebars...
	 */
	private function refresh(){
		$master_blog_id = get_current_blog_id();
		if (! is_multisite()) return;

		$opts = WPLib::inst()->get_settings();
		if (! $opts->component_enabled_shared_sidebars) return;
		if(! is_array($opts->shared_sidebars) || 0 == count($opts->shared_sidebars)) return;

		$blogs = Utils::get_network_sites();


		/**
		 * Get the shared sidebar and widget settings from the "master" blog...
		 */

		$master_sidebars_widgets = get_blog_option($master_blog_id, 'sidebars_widgets');
		if (! $master_sidebars_widgets) return;


		$master_shared_sidebars = array();
		$master_shared_widgets = array();
		$widget_types_to_update = array();
		foreach($opts->shared_sidebars as $sidebar_key){
			$master_shared_sidebars[$sidebar_key] = $master_sidebars_widgets[$sidebar_key];

			if (! is_array($master_shared_sidebars[$sidebar_key])){
				$master_shared_sidebars[$sidebar_key] = array();
			}

			foreach($master_shared_sidebars[$sidebar_key] as $widget_key){
				if ($arr = $this->widget_key_to_type_and_index($widget_key)){
					$widget_types_to_update[] = $arr['type'];
				}
			}
		}
		$widget_types_to_update = array_unique($widget_types_to_update);
		foreach($widget_types_to_update as $widget_type){
			$master_shared_widgets[$widget_type] = get_blog_option($master_blog_id, 'widget_' .$widget_type);
		}


		/**
		 * Loop through the blogs, messing with sidebars and widgets
		 */
		foreach ($blogs as $blog){
			$blog_id = $blog->blog_id;
			if ($master_blog_id == $blog_id) continue;
			$blog_sidebars_widgets = get_blog_option($blog_id, 'sidebars_widgets');
			$blog_widgets_to_update = array();
			foreach($widget_types_to_update as $widget_type){
				$blog_widgets_to_update[$widget_type] = get_blog_option($blog_id, 'widget_' . $widget_type);
				if (! is_array($blog_widgets_to_update[$widget_type])){
					$blog_widgets_to_update[$widget_type] = array();
				}
			}
			foreach($opts->shared_sidebars as $sidebar_key){
				$blog_sidebars_widgets[$sidebar_key] = array();
				foreach($master_shared_sidebars[$sidebar_key] as $widget_key){
					if (! $arr = $this->widget_key_to_type_and_index($widget_key)) continue;
					$widget_type = $arr['type'];
					$widget = $master_shared_widgets[$widget_type][$arr['index']];
					$max = 0;
					foreach(array_keys($blog_widgets_to_update[$widget_type]) as $key){
						if (! is_numeric($key)) continue;
						$max = max($max, $key);
					}
					$max++;
					$blog_widget_key = $widget_type . '-' . $max;
					$blog_sidebars_widgets[$sidebar_key][] = $blog_widget_key;
					$blog_widgets_to_update[$widget_type][$max] = $widget;
				}
			}
			Debugger::inst()->add($blog_sidebars_widgets, '$blog_sidebars_widgets');
			Debugger::inst()->add($blog_widgets_to_update, '$blog_widgets_to_update');



			//clean up...
			foreach($blog_widgets_to_update as $widget_type => $widgets){
				foreach($widgets as $widget_index => $ignore){
					if (! is_numeric($widget_index)) continue;
					$blog_widget_key = $widget_type . '-' . $widget_index;
					$in_a_sidebar = false;
					foreach ($blog_sidebars_widgets as $widget_keys){
						if (is_array($widget_keys) && in_array($blog_widget_key, $widget_keys)){
							$in_a_sidebar = true;
						}
					}
					if (! $in_a_sidebar){
						unset($blog_widgets_to_update[$widget_type][$widget_index]);
					}
				}
			}
			update_blog_option($blog_id, 'sidebars_widgets', $blog_sidebars_widgets);
			foreach($blog_widgets_to_update as $widget_type => $widgets){
				update_blog_option($blog_id, 'widget_' . $widget_type, $widgets);
			}
		}

	}

	/**
	 * Get the widget's type (e.g., 'archives' and numeric index
	 * from a key stored in the 'sidebars_widgets' option. The
	 * actual widgets are stored in the 'widget_type' option,
	 * which are arrays with widgets indexed numerically. Thus,
	 * a widget referred to in 'sidebars_widgets' as 'type-1' would
	 * be found in the option 'widget_type' in the key 1.
	 *
	 * If the $widget_key string matches our regex, we return
	 * an array with 'type' and 'index' keys.
	 *
	 * If the $widget_key string doesn't match, we return false.
	 *
	 * @param $widget_key
	 * @return array|bool
	 */
	private function widget_key_to_type_and_index($widget_key){
		if (! preg_match('/(.+)\-(\d+)$/', $widget_key, $matches)){
			return false;
		}
		return array(
			'type' => $matches[1],
			'index' => intval($matches[2])
		);
	}


} 