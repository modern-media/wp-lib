<?php
use ModernMedia\WPLib\HTML;
/**
 * @var $data_label
 * @var $data_preview_size
 * @var $form_name
 * @var $image_id
 * @var $control_id
 */
?>
<div class="mm-wp-lib-uploader" id="<?php echo $control_id?>"  data-label="<?php echo $data_label?>" data-preview-size="<?php echo $data_preview_size?>">
	<?php
	echo HTML::input_hidden($form_name, $image_id)
	?>
	<div class="holder"></div>
	<p><a href="#" class="choose button"><?php _e('Upload/Choose Image')?></a></p>
	<p><a href="#" class="remove"><?php _e('Remove Image')?></a></p>
</div>
<?php
$jq = '#' . $control_id;
?>
<script type="text/javascript">
if (window.mm_wp_lib_uploader_update){
	window.mm_wp_lib_uploader_update(jQuery('<?php echo $jq?>'));
}
</script>