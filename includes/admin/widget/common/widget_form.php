<?php
namespace ModernMedia\WPLib\Widget;
/**
 * @var BaseWidget $this
 * @var $instance
 */
use ModernMedia\WPLib\Utils;
$instance = $this->merge_instance_defaults($instance);
$opened = isset($instance['widget_opened_form_sections']) ? explode(',', $instance['widget_opened_form_sections']) : array();

?>
<div class="mm-wp-lib-widget-form">
	<?php
	$this->hidden_input($instance, 'widget_opened_form_sections', array('class'=>'widget_opened_form_sections'));
	if (! $this->is_widget_displayed($instance, $reason)) {
		printf(
			'<p style="background-color:#FF0;padding:5px;">This widget will not be displayed. %s</p>',
			$reason
		);
	}

	$this->print_form_fields($instance);
	require Utils::get_lib_path('includes/admin/widget/common/title_form.php');
	require Utils::get_lib_path('includes/admin/widget/common/classes_form.php');
	?>
</div>
 