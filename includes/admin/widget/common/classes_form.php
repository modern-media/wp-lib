<?php
namespace ModernMedia\WPLib\Widget;
use ModernMedia\WPLib\Admin\Controls;
/**
 * @var BaseWidget $this
 * @var $instance
 */
$opened = isset($instance['widget_opened_form_sections']) ? explode(',', $instance['widget_opened_form_sections']) : array();

?>

<div data-section="container_attributes" class="mm-wp-lib-widget-form-section toggleable<?php if(in_array('container_attributes', $opened)) echo ' opened'?>">
	<p class="section-header">
		<a href="#"><i class="toggle-section fa fa-arrow-right<?php if(in_array('container_attributes', $opened)) echo ' fa-rotate-90'?>"></i>
			<?php _e('Container Attributes')?></a>
	</p>


	<div class="form-field">
		<?php
		Controls::attribute_control(
			$this->get_field_name('container_attributes'),
			$instance['container_attributes']
		);

		?>

	</div>
</div>


