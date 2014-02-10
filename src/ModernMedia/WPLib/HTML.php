<?php
namespace ModernMedia\WPLib;
/**
 * Class HTML
 * @package ModernMedia\WPLib
 *
 * Class for spitting out form controls and other pieces of HTML
 */
class HTML {

	/**
	 * Construct a start tag.
	 *
	 * @param $tag
	 * @param array $attrs
	 * @return string
	 */
	public static function tag($tag, $attrs = array()){
		foreach ($attrs as $key => $value){
			$attrs[$key] = sprintf(
				'%s="%s"',
				$key,
				esc_attr($value)
			);
		}

		return sprintf(
			'<%s%s>',
			$tag,
			0 == count($attrs) ? '' : ' ' . implode(' ', $attrs)
		);
	}

	/**
	 * @param $tag
	 * @return string
	 */
	public static function end_tag($tag){
		return sprintf(
			'</%s>',
			$tag
		);
	}


	/**
	 * @param $name
	 * @param $value
	 * @param array $attrs
	 * @return string
	 */
	public static function textarea($name, $value, $attrs = array()){
		$attrs['name'] = $name;
		return self::tag('textarea', $attrs) . $value . self::end_tag('textarea');
	}

	/**
	 * @param $name
	 * @param $value
	 * @param array $attrs
	 * @return string
	 */
	public static function input_text($name, $value, $attrs = array()){
		$attrs['name'] = $name;
		$attrs['value'] = $value;
		if (! isset($attrs['type'])) $attrs['type'] = 'text';
		return self::tag('input', $attrs);
	}

	/**
	 * @param $name
	 * @param array $attrs
	 * @return string
	 */
	public static function input_password($name, $attrs = array()){
		$attrs['name'] = $name;
		$attrs['type'] = 'password';
		return self::tag('input', $attrs);
	}

	/**
	 * @param $name
	 * @param $val
	 * @param array $attrs
	 * @return string
	 */
	public static function input_hidden($name, $val, $attrs = array()){
		$attrs['name'] = $name;
		$attrs['type'] = 'hidden';
		$attrs['value'] = $val;
		return self::tag('input', $attrs);
	}


	/**
	 * @param $name
	 * @param $value
	 * @param $checked
	 * @param array $attrs
	 * @return string
	 */
	public static function input_check($name, $value, $checked, $attrs = array()){
		$attrs['name'] = $name;
		$attrs['type'] = 'checkbox';
		$attrs['value'] = $value;
		if ($checked) $attrs['checked'] = 'checked';
		return self::tag('input', $attrs);
	}

	/**
	 * @param $name
	 * @param $checked
	 * @param array $attrs
	 * @return string
	 */
	public static function input_single_check($name, $checked, $attrs = array()){
		return self::input_check($name, '1', $checked, $attrs );
	}


	/**
	 * @param $name
	 * @param $options
	 * @param array $checked_values
	 * @param array $args
	 * @return string
	 */
	public static function checkboxes($name, $options, $checked_values = array(), $args = array()){
		$defaults = array(
			'template' => '<label class="checkbox">%1$s %2$s</label>',
			'attrs' => array()
		);
		$args = array_merge($defaults, $args);
		$html = '';
		foreach($options as $value => $label){
			$checkbox = self::input_check($name . '[]', $value, in_array($value, $checked_values), $args['attrs']);
			$html .= sprintf($args['template'], $checkbox, $label);
		}
		return $html;

	}


	/**
	 * @param $name
	 * @param $value
	 * @param $checked
	 * @param array $attrs
	 * @return string
	 */
	public static function input_radio($name, $value, $checked, $attrs = array()){
		$attrs['name'] = $name;
		$attrs['type'] = 'radio';
		$attrs['value'] = $value;
		if ($checked) $attrs['checked'] = 'checked';
		return self::tag('input', $attrs);
	}


	/**
	 * @param $name
	 * @param $options
	 * @param string $checked_value
	 * @param array $args
	 * @return string
	 */
	public static function radios($name, $options, $checked_value = '', $args = array()){
		$defaults = array(
			'template' => '<label class="checkbox">%1$s %2$s</label>',
			'attrs' => array()
		);
		$args = array_merge($defaults, $args);
		$html = '';
		foreach($options as $value => $label){
			$radio = self::input_radio($name, $value, $value == $checked_value, $args['attrs']);
			$html .= sprintf($args['template'], $radio, $label);
		}
		return $html;

	}

	/**
	 * @param $name
	 * @param $options
	 * @param string $selected
	 * @param array $attrs
	 * @param array $args
	 * @return string
	 */
	public static function select($name, $options, $selected = '', $attrs = array(), $args = array()){
		$attrs['name'] = $name;
		$html = self::tag('select', $attrs);

		$defaults = array(
			'please_select' => 'Please select...',
			'please_select_value' => '',
		);
		$args = array_merge($defaults, $args);

		if (! empty($args['please_select'])){
			$html .= self::tag('option', array('value' => $args['please_select_value']));
			$html .= $args['please_select'];
			$html .= self::end_tag('option');
		}

		foreach($options as $value => $label){
			$attrs = array('value' => $value);
			if ($value == $selected) $attrs['selected'] = 'selected';
			$html .= self::tag('option', $attrs);
			$html .= $label;
			$html .= self::end_tag('option');
		}
		$html .= self::end_tag('select');
		return $html;
	}

	public static function attr_array_to_string($arr){
		$attrs = array();
		foreach($arr as $key => $value){
			if (empty($value)) continue;
			$attrs[] = sprintf(
				'%s="%s"', $key, esc_attr($value)
			);
		}
		return implode(' ', $attrs);
	}
}