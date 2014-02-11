<?php
namespace ModernMedia\WPLib\Widget;
use ModernMedia\WPLib\Debugger;
use ModernMedia\WPLib\HTML;
use ModernMedia\WPLib\Utils;
use ModernMedia\WPLib\WPLib;

/**
 * Class BaseWidget
 * @package ModernMedia\WPLib\Widget
 *
 * The base class for a variety of custom widgets
 */
abstract class BaseWidget extends \WP_Widget {


	/**
	 * Return an array of instance default variable values.
	 * Child classes should merge with the sensible defaults defined
	 * below in this parent class.
	 *
	 * @return array
	 */
	abstract public function get_instance_defaults();

	/**
	 * A method that allows us to display a warning message
	 * in the widget admin, and prevent mis-configured
	 * widgets from being displayed on the front end.
	 *
	 * @param $instance
	 * @param $reason
	 * @return bool
	 */
	abstract public function is_widget_displayed($instance, &$reason);



	/**
	 * Where the child classes do their
	 * front end work. Called from widget()
	 *
	 * @param $args
	 * @param $instance
	 * @return string
	 */
	abstract public function get_widget_content($args, $instance);

	/**
	 *  Where the child classes do their
	 *  form fields. Called from form()
	 *
	 * @param $instance
	 * @return void
	 */
	abstract public function print_form_fields($instance);


	/**
	 * Giving the child classes a chance to
	 * modify instance variables.
	 *
	 * @param $instance
	 * @return void
	 */
	abstract public function validate(&$instance);


	/**
	 * Return the name of the widget
	 *
	 * @return string
	 */
	public function get_name(){
		$class = explode('\\', get_class($this));
		$class = array_pop($class);
		$widgets = WPLib::inst()->get_widgets();
		if (isset($widgets[$class])) return $widgets[$class]['name'];
		return __('No widget name available.');
	}

	/**
	 * Return the description of the widget...
	 *
	 * @return string
	 */
	public function get_desc(){
		$class = explode('\\', get_class($this));
		$class = array_pop($class);
		$widgets = WPLib::inst()->get_widgets();
		if (isset($widgets[$class])) return $widgets[$class]['description'];
		return __('No description available.');
	}




	/**
	 * the constructor
	 */
	public function __construct() {
		parent::__construct($this->get_id_base(), $this->get_name(), $this->get_options(), $this->get_control_options() );
	}

	/**
	 * Return this id base...
	 *
	 * @return string
	 */
	public function get_id_base(){
		$class = explode('\\', get_class($this));
		$class = array_pop($class);
		$widgets = WPLib::inst()->get_widgets();
		if (isset($widgets[$class])) return 'mm-wplib-' . $class;
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
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update($new_instance, $old_instance){
		$instance = Utils::trim_stripslashes_deep($new_instance);
		if (! is_string($instance['widget_opened_form_sections'])){
			$instance['widget_opened_form_sections'] = '';
		}
		if (! is_array($instance['container_attributes'])){
			$instance['container_attributes'] = array();
		}
		$this->validate($instance);
		return $instance;
	}



	public function attribute_field_to_keyed_array($attrs){
		$keyed = array();
		foreach($attrs as $a){
			if (empty($a['attribute_name']) || empty($a['attribute_value'])){
				continue;
			}
			$keyed[$a['attribute_name']] = $a['attribute_value'];
		}
		return $keyed;
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
		$attrs = is_array($instance['container_attributes']) ? $instance['container_attributes'] : array();
		$attrs = $this->attribute_field_to_keyed_array($attrs);
		$attrs = HTML::attr_array_to_string($attrs);


		if (! empty($attrs)){
			$before_widget = preg_replace('/^<([a-z]+)/', '<$1 ' . $attrs . ' ', $before_widget );
		}

		echo $before_widget;
		echo $this->get_widget_content($args, $instance);


		echo  $args['after_widget'];
	}

	/**
	 * @param array $instance
	 * @return string|void
	 */
	public function form($instance){
		$instance = $this->merge_instance_defaults($instance);
		echo '<div class="mm-wp-lib-widget-form">' . PHP_EOL;
		$this->hidden_input($instance, 'widget_opened_form_sections', array('class'=>'widget_opened_form_sections'));
		if (! $this->is_widget_displayed($instance, $reason)) {
			printf(
				'<div class="mm-wp-lib-widget-error">%s %s</div>',
				__('This widget will not be displayed.'),
				$reason
			);
		}
		$this->print_form_fields($instance);
		require Utils::get_lib_path('includes/admin/widget/common/classes_form.php');
		echo '</div>'. PHP_EOL;

	}

	/**
	 * @param $instance
	 * @return array
	 */
	public function merge_instance_defaults($instance){
		$defaults = $this->get_instance_defaults();
		$defaults = array_merge(
			array(
				'widget_opened_form_sections' => '',
				'container_attributes' => array(),
			),
			$defaults
		);
		return array_merge($defaults, $instance);

	}


	/**
	 * @param $instance
	 * @param $field
	 * @param $label
	 * @param $post_type
	 * @param string $please
	 */
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