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

		if (isset($_POST) && isset($_POST['savewidgets'])) {
			add_filter('wp_die_ajax_handler', array($this, '_filter_wp_die_ajax_handler'), 10, 1);
		}

	}
	public function _filter_wp_die_ajax_handler($handler){
		$this->refresh();
		return $handler;
	}

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
					if ($max == 0){
						$max = 1;
					}
					$blog_widget_key = $widget_type . '-' . $max;
					$blog_sidebars_widgets[$sidebar_key][] = $blog_widget_key;
					$blog_widgets_to_update[$widget_type][$max] = $widget;
				}
			}



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