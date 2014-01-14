<?php
namespace ModernMedia\WPLib;
class Utils {

	/**
	 * Whether or not the request method is 'POST' -- i.e. submitting a form
	 * @return bool
	 */
	public static function is_submitting(){
		return (strcasecmp('POST', $_SERVER['REQUEST_METHOD'] ) == 0);
	}

	/**
	 * @param array $arr
	 * @param array $whitelist
	 * @param array $blacklist
	 * @return array
	 */
	public static function whitelist_blacklist_array($arr, $whitelist = array(), $blacklist = array()){
		if (! is_array($arr)) return array();

		if (is_array($whitelist) && count($whitelist)){
			$arr = array_intersect($arr, $whitelist);
		}
		if (is_array($blacklist) && count($blacklist)){
			$arr = array_diff($arr, $blacklist);
		}
		return $arr;
	}

	/**
	 * Provides a replacement for WP's native stripslashes_deep
	 * function, which, frustratingly, doesn't trim
	 *
	 * @param $value
	 * @return array|object|string
	 */
	public static function trim_stripslashes_deep($value){
		if ( is_array($value) ) {
			$value = array_map(array(get_called_class(), 'trim_stripslashes_deep'), $value);
		} elseif ( is_object($value) ) {
			$vars = get_object_vars( $value );
			foreach ($vars as $key=>$data) {
				$value->{$key} = self::trim_stripslashes_deep( $data );
			}
		} elseif ( is_string( $value ) ) {
			$value = trim(stripslashes($value));
		}
		return $value;
	}

	/**
	 * @return array
	 */
	public static function get_timezone_auto_complete_options(){
		$tzs = \DateTimeZone::listIdentifiers();
		$timezones = array();
		foreach($tzs as $tz){
			$timezones[] = array(
				'value' => $tz,
				'label' => str_replace('_', ' ', $tz),
			);
		}

		$abbrs = \DateTimeZone::listAbbreviations();
		foreach ($abbrs as $abbr => $arr){
			$timezones[] = array(
				'label' => strtoupper($abbr),
				'value' => $arr[0]['timezone_id'],
			);
		}
		return $timezones;
	}

	/**
	 * @param $seconds
	 * @return string
	 */
	public static function get_seconds_in_english($seconds){
		$seconds_in_minute = 60;
		$seconds_in_hour = $seconds_in_minute * 60;
		$seconds_in_day = $seconds_in_hour * 24;

		$days = floor($seconds/$seconds_in_day);
		$left_over = $seconds - ($days * $seconds_in_day);
		$hours = floor($left_over/$seconds_in_hour);
		$left_over = $left_over - ($hours * $seconds_in_hour);
		$minutes = floor($left_over/$seconds_in_minute);
		$secs = $left_over - ($minutes * $seconds_in_minute);

		$str = array();
		if ($days > 0) {
			$str[] = sprintf(
				'%1$s %2$s',
				$days,
				$days > 1 ? __('days') : __('day')
			);
		}
		if ($hours > 0) {
			$str[] = sprintf(
				'%1$s %2$s',
				$hours,
				$hours > 1 ? __('hours') : __('hour')
			);
		}
		if ($minutes > 0) {
			$str[] = sprintf(
				'%1$s %2$s',
				$minutes,
				$minutes > 1 ? __('minutes') : __('minute')
			);
		}
		if ($secs > 0) {
			$str[] = sprintf(
				'%1$s %2$s',
				$secs,
				$secs > 1 ? __('seconds') : __('second')
			);
		}

		return implode( ', ', $str);
	}

	/**
	 * @param $singular
	 * @param $plural
	 * @param array $labels
	 * @return array
	 */
	public static function get_post_type_labels($singular, $plural = '', $labels = array()){
		$uc_singular = ucwords($singular);
		if (empty($plural)) $plural = $singular . 's';
		$uc_plural = ucwords($plural);
		$lc_plural = strtolower($plural);
		$defaults = array(
			'name' => $uc_plural,
			'singular_name' => $uc_singular,
			'add_new' => 'Add New',
			'add_new_item' => 'Add New ' . $uc_singular,
			'edit_item' => 'Edit ' . $uc_singular,
			'new_item' => 'New ' . $uc_singular,
			'all_items' => 'All ' . $uc_plural,
			'view_item' => 'View ' . $uc_singular,
			'search_items' => 'Search ' . $uc_plural,
			'not_found' =>  'No ' . $lc_plural . ' found',
			'not_found_in_trash' => 'No ' . $lc_plural . ' found in Trash',
			'parent_item_colon' => '',
			'menu_name' => $uc_plural
		);
		if (! is_array($labels)) $labels = array();
		return array_merge($defaults, $labels);
	}


	/**
	 * @param $singular
	 * @param $plural
	 * @param array $labels
	 * @return array
	 */
	public static function get_tax_labels($singular, $plural = '', $labels = array()){
		$uc_singular = ucwords($singular);
		if (empty($plural)) $plural = $singular . 's';
		$uc_plural = ucwords($plural);
		$lc_plural = strtolower($plural);
		$defaults =  array(
			'name'                       => _x( $uc_plural, 'taxonomy general name' ),
			'singular_name'              => _x( $uc_singular, 'taxonomy singular name' ),
			'search_items'               => __( 'Search ' . $uc_plural),
			'popular_items'              => __( 'Popular '  . $uc_plural),
			'all_items'                  => __( 'All ' .  $uc_plural),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit ' .  $uc_singular),
			'update_item'                => __( 'Update ' .  $uc_singular ),
			'add_new_item'               => __( 'Add New ' .  $uc_singular),
			'new_item_name'              => __( 'New ' .  $uc_singular . ' Name' ),
			'separate_items_with_commas' => __( 'Separate ' .  $lc_plural . ' with commas' ),
			'add_or_remove_items'        => __( 'Add or remove ' . $lc_plural),
			'choose_from_most_used'      => __( 'Choose from the most used ' . $lc_plural),
			'not_found'                  => __( 'No ' . $lc_plural .' found.' ),
			'menu_name'                  => __( $uc_plural ),
		);
		if (! is_array($labels)) $labels = array();
		return array_merge($defaults, $labels);
	}

	public static function member_or_default($key, $arr, $default = null){
		return isset($arr[$key]) ? $arr[$key] : $default;
	}
	public static function request_or_default($key, $default = null){
		return self::member_or_default($key, $_REQUEST, $default);
	}

	/**
	 * @param \WP_Query $query
	 * @param $args
	 * @return string|null
	 */
	public static function post_type_dropdown($query, $args){


		$default = array(
			'depth' => 0,
			'selected' => 0,
			'echo' => false,
			'name' => 'post_id',
			'id' => 'post_id',
			'show_option_none' => 'Please select...',
			'option_none_value' => ''
		);
		$args = wp_parse_args($args, $default);


		$out = sprintf(
			'<select name="%s" id="%s">
			%s
			%s
			</select>',
			$args['name'],
			$args['id'],
			empty($args['show_option_none']) ?
				'' :
				sprintf(
					'<option value="%s">%s</option>',
					$args['option_none_value'],
					$args['show_option_none']
				),
			walk_page_dropdown_tree($query->posts, $args['depth'], $args)
		);
		if ($args['echo']) echo $out;
		return $out;

	}

	/**
	 * @param string $p
	 * @return string
	 */
	public static function get_lib_path($p = ''){
		$path = dirname(dirname(dirname(__DIR__)));
		$p = trim(trim($p), DIRECTORY_SEPARATOR);
		if (! empty($p)) $path .= DIRECTORY_SEPARATOR . $p;
		return $path;
	}

	/**
	 * @param string $path
	 * @return string
	 */
	public static  function get_lib_uri($path = ''){
		$dummy = self::get_lib_path('dummy');
		return plugins_url($path, $dummy);
	}
} 