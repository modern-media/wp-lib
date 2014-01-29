<?php
namespace ModernMedia\WPLib\Widget;
use ModernMedia\WPLib\Helper\HTML;
use ModernMedia\WPLib\Utils;

/**
 * Class BaseWidget
 * @package ModernMedia\WPLib\Widget
 *
 * The base class for a variety of custom widgets
 */
abstract class BaseWidget extends \WP_Widget {


	/**
	 * @return array
	 */
	abstract public function get_instance_defaults();

	/**
	 * @param $instance
	 * @param $reason_not_displayed
	 * @return bool
	 */
	abstract public function is_widget_displayed($instance, &$reason_not_displayed);


	/**
	 * @param $instance
	 * @return bool
	 */
	abstract public function is_widget_content_displayed($instance);



	/**
	 * @param $instance
	 * @return string
	 */
	abstract public function get_widget_content($instance);

	/**
	 * @param $instance
	 * @return void
	 */
	abstract public function print_form_fields($instance);


	/**
	 * @param $instance
	 * @return void
	 */
	abstract public function validate(&$instance);


	/**
	 * @return string
	 */
	abstract public function get_name();

	/**
	 * @return string
	 */
	abstract public function get_desc();




	/**
	 * the constructor
	 */
	public function __construct() {
		parent::__construct($this->get_id_base(), $this->get_name(), $this->get_options(), $this->get_control_options() );
	}

	public function get_id_base(){
		return str_replace('\\', '_', get_class($this));
	}

	/**
	 * @return array
	 */
	public function get_options(){
		return array(
			'description' => $this->get_desc()
		);
	}

	/**
	 * @return array
	 */
	public function get_control_options(){
		return array();
	}


	/**
	 * @param $instance
	 * @return string
	 */
	protected function get_instance_extra_classes($instance){
		return $instance['extra_classes'];
	}

	/**
	 * @param $val
	 * @return string
	 */
	protected function trim_and_stripslash($val){
		return trim(stripslashes($val));
	}

	/**
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update($new_instance, $old_instance){
		$instance = array_map(array($this, 'trim_and_stripslash'), $new_instance);
		$this->validate($instance);
		return $instance;
	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	public function widget($args, $instance){
		$instance = $this->merge_instance_defaults($instance);
		if (! $this->is_widget_displayed($instance, $reason)) {
			return;
		}



		
		$before_widget = $args['before_widget'];
		$extra_classes = trim($instance['extra_classes']);
		if (! empty($extra_classes)){
			$before_widget = str_replace('class="', 'class="' . $extra_classes . ' ', $before_widget );
		}

		echo $before_widget;

		if ($this->is_widget_title_displayed($instance)){
			$text = $this->get_instance_title_text($instance);
			$label = sprintf('<span class="text">%s</span>', $text);
			$val = trim($instance['title_data_icon']);
			if (! empty($val)){
				$label = sprintf('<span data-icon="&#x%s;"></span> %s', dechex($val), $label);
			}
			$val = trim($instance['title_link']);
			if (! empty($val)){
				$label = sprintf('<a href="%s" title="%s">%s</a>', $val, esc_attr($text), $label);
			}
			printf(
				'%s%s%s',
				$args['before_title'],
				$label,
				$args['after_title']
			);

		}
		if ($this->is_widget_content_displayed($instance)){
			printf(
				'%s%s%s',
				isset($args['before_content']) ? $args['before_content'] : '',
				$this->get_widget_content($instance),
				isset($args['after_content']) ? $args['after_content'] : ''
			);

		}

		echo  $args['after_widget'];
	}

	/**
	 * @param array $instance
	 * @return string
	 */
	protected function get_instance_title_text($instance){
		$text = isset($instance['title']) ? trim($instance['title']) : '';
		return $text;
	}

	/**
	 *
	 * Default/ override to get rid of title
	 * @return bool
	 */
	public function does_widget_have_title_option(){
		return true;
	}

	/**
	 * @return bool
	 */
	public function does_widget_have_title_link_option() {
		return true;
	}


	/**
	 * @param array $instance
	 * @return string
	 */
	protected function is_widget_title_displayed($instance){
		return 1 == $instance['display_title'];
	}



	/**
	 * @param array $instance
	 * @return string|void
	 */
	public function form($instance){
		require Utils::get_lib_path('includes/admin/widget/common/widget_form.php');
	}

	/**
	 * @param $instance
	 * @return array
	 */
	public function merge_instance_defaults($instance){
		$defaults = $this->get_instance_defaults();
		$defaults = array_merge(
			array(
				'widget_form_advanced_is_open' => false,
				'widget_opened_form_sections' => '',
				'extra_classes' => '',
				'extra_attributes' => '',
				'display_title' => false,
				'title' => '',
				'title_link' => '',
			),
			$defaults
		);
		return array_merge($defaults, $instance);

	}

	public function print_post_type_select($instance, $field, $label, $post_type, $please = 'Please select...'){
		$field_id = $this->get_field_id($field);
		$field_name = $this->get_field_name($field);
		$field_value = $instance[$field];
		$q = new \WP_Query(
			array(
				'post_type'=>$post_type,
				'posts_per_page' => -1,
				'order' => 'ASC',
				'orderby' => 'title'
			)
		);
		$select = Utils::post_type_dropdown(
			$q,
			array(
				'selected' => $field_value,
				'name' => $field_name,
				'id' => $field_value,
				'show_option_none' => $please,
				'option_none_value' => '0',
			)
		);
		printf(
			'<p><label for="%s">%s</label> %s</p>',
			$field_id,
			__($label),
			$select
		);
	}

	/**
	 * @param $instance
	 * @param $field
	 * @param array $attr
	 * @param bool $echo
	 * @return string|null
	 */
	public function text_input($instance, $field, $attr = array(), $echo = true){
		$attr['id'] = $this->get_field_id($field);
		$html = HTML::input_text($this->get_field_name($field), $instance[$field], $attr);
		if ($echo) {
			echo $html;
			return null;
		}
		else return $html;
	}

	/**
	 * @param $instance
	 * @param $field
	 * @param array $attr
	 * @param bool $echo
	 * @return null|string
	 */
	public function text_area($instance, $field, $attr = array(), $echo = true){
		$attr['id'] = $this->get_field_id($field);

		$html = HTML::textarea($this->get_field_name($field), $instance[$field], $attr);
		if ($echo) {
			echo $html;
			return null;
		}
		else return $html;
	}

	/**
	 * @param $instance
	 * @param $field
	 * @param array $attr
	 * @param bool $echo
	 * @return string|null
	 */
	public function hidden_input($instance, $field, $attr = array(), $echo = true){
		$attr['id'] = $this->get_field_id($field);

		$html = HTML::input_hidden($this->get_field_name($field), $instance[$field], $attr);
		if ($echo) {
			echo $html;
			return null;
		}
		else return $html;
	}

	/**
	 * @param $instance
	 * @param $field
	 * @param $options
	 * @param array $attr
	 * @param bool $echo
	 * @param string $please
	 * @param string $please_value
	 * @return null|string
	 */
	public function select($instance, $field, $options, $attr = array(),  $echo = true, $please = 'Please select...', $please_value = ''){
		$attr['id'] = $this->get_field_id($field);
		$html = HTML::select(
			$this->get_field_name($field),
			$options,
			$instance[$field],
			$attr,
			array(
				'please_select' => $please,
				'please_select_value' => $please_value,
			)

		);
		if ($echo) {
			echo $html;
			return null;
		}
		else return $html;
	}

	/**
	 * @param $instance
	 * @param $field
	 * @param $label
	 * @param array $attr
	 * @param bool $echo
	 * @return null|string
	 */
	public function checkbox_input($instance, $field, $label, $attr = array(), $echo = true){
		$attr['id'] = $this->get_field_id($field);
		$html = HTML::input_single_check($this->get_field_name($field), 1 == $instance[$field], $attr);

		$html = sprintf (
			'<label for="%s">%s %s</label>',
			$attr['id'],
			$html,
			$label
		);

		if ($echo) {
			echo $html;
			return null;
		}
		else return $html;

	}



}