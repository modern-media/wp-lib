<?php
namespace ModernMedia\WPLib;
use ModernMedia\WPLib\Helper\HTML;
use ModernMedia\WPLib\Admin\WPLibSettingsPanel;
/**
 * @var WPLibSettingsPanel $this
 */
$wp_lib = WPLib::inst();
$settings = $wp_lib->get_settings();

?>

<form method="post" action="<?php echo $this->get_panel_url($this->get_id())?>">
	<?php
	$this->echo_nonce();
	?>

	<h3><?php _e('Amazon Web Services S3 Settings')?></h3>
	<p>
		<?php
		echo HTML::input_single_check('component_enabled_awss3', $settings->component_enabled_awss3);
		?>
		<label for="component_enabled_awss3">
			<?php _e('Enable S3 storage for uploads.')?>
		</label>
	</p>
	<table class="form-table">
		<tbody>
		<tr>
			<th scope="row">
				<label for="awss3_id"><?php _e('Access Key ID')?></label>
			</th>
			<td>
				<input
					class="regular-text"
					type="text"
					name="awss3_id"
					id="awss3_id"
					value="<?php echo esc_attr($settings->awss3_id)?>"
					>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="awss3_secret"><?php _e('Access Key Secret')?></label>
			</th>
			<td>
				<input
					class="regular-text"
					type="text"
					name="awss3_secret"
					id="awss3_secret"
					value="<?php echo esc_attr($settings->awss3_secret)?>"
					>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="awss3_bucket"><?php _e('Bucket')?></label>
			</th>
			<td>
				<?php
				echo HTML::input_text('awss3_bucket',$settings->awss3_bucket);
				?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<a href="#" class="check-aws"><?php _e('Check these settings')?></a>
				<div class="ajax-msg" style="display:none;"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($){
						var nonce = <?php echo json_encode($this->get_ajax_nonce_value('check_aws_settings'));?>;
						var action = <?php echo json_encode($this->ajax_action_from_action('check_aws_settings'));?>;
						$('.check-aws').click(function(){

							var t = $(this).parents('table');
							var msg = $('.ajax-msg', t);

							msg.removeClass('error');
							msg.removeClass('success');
							msg.addClass('wait');
							msg.html('<p>Please wait.</p>');
							msg.slideDown('fast');

							var o = {
								nonce: nonce,
								action: action
							};
							$('input', t).each(function(){
								o[$(this).attr('name')] = $(this).val();
							});
							$.post(ajaxurl, o, function(response){
								nonce = response.nonce;
								msg.removeClass('wait');
								if(response.is_error){
									msg.addClass('error');
									msg.html(response.error_html);
									msg.slideDown('fast');
								} else {
									msg.addClass('success');
									msg.html('<p>' + response.data + '</p>');
									msg.slideDown('fast');
								}
							}, 'json');
						});
					});
				</script>
			</td>
		</tr>
		</tbody>
	</table>
	<?php
	submit_button('Save');
	?>
</form>
