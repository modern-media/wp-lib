<?php
namespace ModernMedia\WPLib;
use ModernMedia\WPLib\Admin\Panel\WPLibSettingsPanel;
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

	<div class="mm-wp-lib-panel-form-section">
		<h3><?php _e('Amazon Web Services S3 Settings')?></h3>
		<div class="mm-form-field horizontal check">
			<label>
				<?php echo HTML::input_single_check('component_enabled_awss3', $settings->component_enabled_awss3);?>
				<span><?php _e('Enable S3 storage for uploads')?></span>
			</label>
		</div>
		<div class="mm-form-field horizontal">
			<div class="form-label">
				<label for="awss3_id"><?php _e('Access Key ID')?></label>
			</div>
			<div class="form-controls">
				<input
					class="widefat"
					type="text"
					name="awss3_id"
					id="awss3_id"
					value="<?php echo esc_attr($settings->awss3_id)?>"
					>
			</div>
		</div>
		<div class="mm-form-field horizontal">
			<div class="form-label">
				<label for="awss3_secret"><?php _e('Access Key Secret')?></label>
			</div>
			<div class="form-controls horizontal">
				<input
					class="widefat"
					type="text"
					name="awss3_secret"
					id="awss3_secret"
					value="<?php echo esc_attr($settings->awss3_secret)?>"
					>
			</div>
		</div>
		<div class="mm-form-field horizontal">
			<div class="form-label">
				<label for="awss3_bucket"><?php _e('Bucket')?></label>
			</div>
			<div class="form-controls">
				<?php
				echo HTML::input_text(
					'awss3_bucket',
					$settings->awss3_bucket,
					array(
						'class' => 'widefat',
						'placeholder' => __('Bucket Name')
					)
				);
				?>
			</div>
		</div>
		<div class="help horizontal">
			<p><a href="#" class="check-aws"><?php _e('Check these settings')?></a></p>
			<div class="ajax-msg" style="display:none;"></div>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				var nonce = <?php echo json_encode($this->get_ajax_nonce_value('check_aws_settings'));?>;
				var action = <?php echo json_encode($this->ajax_action_from_action('check_aws_settings'));?>;
				$('.check-aws').click(function(evt){
					evt.preventDefault();
					var t = $(this).parents('.mm-wp-lib-panel-form-section');
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

	</div>

	<?php
	if (is_multisite()){
		$blogs = Utils::get_network_sites();
		$sidebars = array();
		$sidebar_ids = array();
		$curr = get_current_blog_id();
		foreach($blogs as $blog){
			switch_to_blog($blog->blog_id);
			$ids = array_keys(wp_get_sidebars_widgets());
			$sidebar_ids = array_merge($sidebar_ids, $ids);
			$sidebars[$blog->blog_id] = $ids;
		}
		$shareable = array();
		switch_to_blog($curr);

		foreach($sidebar_ids as $id){
			if ('wp_inactive_widgets' == $id) continue;
			$in_all = true;
			foreach($sidebars as $arr){
				if (! in_array($id, $arr)){
					$in_all = false;
				}
			}
			if ($in_all){
				$shareable = array_merge($shareable, array($id));
			}
		}
		$shareable = array_unique($shareable);
		?>
		<div class="mm-wp-lib-panel-form-section">
			<h3><?php _e('Network: Shared Sidebars')?></h3>
			<div class="mm-form-field horizontal check">
				<label>
					<?php echo HTML::input_single_check('component_enabled_shared_sidebars', $settings->component_enabled_shared_sidebars);?>
					<span><?php _e('Enable shared sidebars between sites on the network')?></span>
				</label>
			</div>
			<div class="mm-form-field horizontal">
				<div class="form-label">
					<?php _e('Shared Sidebars')?>
				</div>
				<div class="form-controls">
					<?php
					foreach($shareable as $id){
						?>

						<label class="checkbox-block">
							<?php
							echo HTML::input_check('shared_sidebars[]', $id, in_array($id,$settings->shared_sidebars));
							?>
							<span><?php echo $id?></span>
						</label>

					<?php
					}
					?>
				</div>
			</div>
		</div>

		<?php
	}
	?>
	<div class="mm-wp-lib-panel-form-section">
		<h3><?php _e('Default Site Meta Tags')?></h3>
		<div class="mm-form-field horizontal">
			<div class="form-label">
				<label for="meta_tags_default_site_description"><?php _e('Meta Description')?></label>
			</div>
			<div class="form-controls">
				<input
					type="text"
					class="widefat"
					name="meta_tags_default_site_description"
					id="meta_tags_default_site_description"
					placeholder="<?php echo esc_attr(__('Default description of your site'))?>"
					value="<?php echo esc_attr($settings->meta_tags_default_site_description) ?>"
					>
				<p>
					<?php _e('Characters:')?> <span class="char-count" data-target="#meta_tags_default_site_description"></span>
				</p>
			</div>
		</div>
		<div class="mm-form-field horizontal">
			<div class="form-label">
				<label for="meta_tags_og_description"><?php _e('Open Graph Description')?></label>
			</div>
			<div class="form-controls">
				<input
					type="text"
					class="widefat"
					name="meta_tags_og_description"
					id="meta_tags_og_description"
					placeholder="<?php echo esc_attr(__('Default og:description of your site'))?>"
					value="<?php echo esc_attr($settings->meta_tags_og_description) ?>"
					>
				<p>
					<?php _e('Characters:')?> <span class="char-count" data-target="#meta_tags_og_description"></span>
				</p>
			</div>
		</div>

		<div class="help horizontal">
			<h4><?php _e('og:image Tag Image Sizes')?></h4>
			<p>
			<?php
			printf(
				__('The recommended image size for Facebook images
				(the og:image meta tag) is %s px wide by %s px high, but
				you can set custom sizes below. A version of all uploaded
				images will be created at this size, and that image will
				be used in the og:image meta tag.'
				),
				MetaTags::OG_IMAGE_WIDTH,
				MetaTags::OG_IMAGE_HEIGHT
			)
			?>
			</p>
		</div>
		<div class="mm-form-field horizontal">
			<div class="form-label">
				<label for="meta_tags_og_image_width">
					<?php _e('Width')?>
				</label>
			</div>
			<div class="form-controls">
				<input
					type="number"
					name="meta_tags_og_image_width"
					id="meta_tags_og_image_width"
					placeholder="<?php echo esc_attr(__('Recommended: ')) . MetaTags::OG_IMAGE_WIDTH?>"
					value="<?php echo esc_attr($settings->meta_tags_og_image_width)?>"
					size="7"
					>
			</div>

		</div>
		<div class="mm-form-field horizontal">
			<div class="form-label">
				<label for="meta_tags_og_image_height">
					<?php _e('Height')?>
				</label>
			</div>
			<div class="form-controls">
				<input
					type="number"
					name="meta_tags_og_image_height"
					id="meta_tags_og_image_height"
					placeholder="<?php echo esc_attr(__('Recommended: ')) . MetaTags::OG_IMAGE_HEIGHT?>"
					value="<?php echo esc_attr($settings->meta_tags_og_image_height)?>"
					size="7"
					>
			</div>

		</div>
		<div class="mm-form-field horizontal">
			<div class="form-label">
				<label><?php _e('Default Site Image')?></label>
			</div>
			<div class="form-controls">
				<div
					class="mm-wp-lib-uploader"
					data-label="<?php _e('Choose Site Image')?>"
					data-preview-size="medium"
					>
					<input type="hidden" name="meta_tags_og_image_id" value="<?php echo $settings->meta_tags_og_image_id?>">
					<div class="holder"></div>
					<p><a href="#" class="choose button">
							<?php _e('Upload/Choose Site Image')?>
						</a></p>
					<p><a href="#" class="remove"><?php _e('Remove Image')?></a></p>

				</div>
			</div>
		</div>
	</div>








	<div class="mm-wp-lib-panel-form-section">
		<h3><?php _e('Social Sharing Options')?></h3>

		<div class="mm-form-field horizontal">
			<div class="form-label">
				<label for="facebook_app_id"><?php _e('Facebook App ID')?></label>
			</div>
			<div class="form-controls">
				<input
					placeholder="<?php echo esc_attr( __('Facebook App ID'))?>"
					type="text"
					name="facebook_app_id"
					id="facebook_app_id"
					value="<?php echo esc_attr($settings->facebook_app_id)?>"
					class="widefat"
					>
			</div>
		</div>


		<div class="mm-form-field check horizontal">
			<label>
				<?php echo HTML::input_single_check('enable_share_this', $settings->enable_share_this)?>
				<span><?php _e('Enable ShareThis buttons')?></span>
			</label>
		</div>
		<div class="mm-form-field horizontal">
			<div class="form-label">
				<label for="share_this_publisher_key"><?php _e('ShareThis Publisher Key')?></label>
			</div>
			<div class="form-controls">
				<input
					placeholder="<?php echo esc_attr( __('ShareThis Publisher Key'))?>"
					type="text"
					name="share_this_publisher_key"
					id="share_this_publisher_key"
					value="<?php echo esc_attr($settings->share_this_publisher_key)?>"
					class="widefat"
					>
			</div>
		</div>
	</div>

	<div class="mm-wp-lib-panel-form-section">
		<h3><?php _e('SMTP Mail Settings')?></h3>

		<div class="mm-form-field horizontal">
			<div class="form-label">
				<label for="smtp_server"><?php _e('Server')?></label>
			</div>
			<div class="form-controls">
				<input
					placeholder="<?php echo esc_attr( __('server.example.com'))?>"
					type="text"
					name="smtp_server"
					id="smtp_server"
					value="<?php echo esc_attr($settings->smtp_server)?>"
					>
			</div>
		</div>
		<div class="mm-form-field horizontal">
			<div class="form-label">
				<label for="smtp_username"><?php _e('Username')?></label>
			</div>
			<div class="form-controls">
				<input
					placeholder="<?php echo esc_attr( __('Username'))?>"
					type="text"
					name="smtp_username"
					id="smtp_username"
					value="<?php echo esc_attr($settings->smtp_username)?>"
					>
			</div>
		</div>
		<div class="mm-form-field horizontal">
			<div class="form-label">
				<label for="smtp_password"><?php _e('Password')?></label>
			</div>
			<div class="form-controls">
				<input
					placeholder="<?php echo esc_attr( __('Password'))?>"
					type="text"
					name="smtp_password"
					id="smtp_password"
					value="<?php echo esc_attr($settings->smtp_password)?>"
					>
			</div>
		</div>
		<div class="mm-form-field horizontal">
			<div class="form-label">
				<label for="smtp_port"><?php _e('Port')?></label>
			</div>
			<div class="form-controls">
				<input
					placeholder="<?php echo esc_attr( __('587'))?>"
					size="6"
					type="text"
					name="smtp_port"
					id="smtp_port"
					value="<?php echo esc_attr($settings->smtp_port)?>"
					>
			</div>
		</div>
		<div class="help horizontal">
			<p><a href="#" class="check-smtp"><?php _e('Check these settings')?></a></p>
			<div class="ajax-msg" style="display:none;"></div>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				var nonce = <?php echo json_encode($this->get_ajax_nonce_value('check_smtp_settings'));?>;
				var action = <?php echo json_encode($this->ajax_action_from_action('check_smtp_settings'));?>;
				$('.check-smtp').click(function(evt){
					evt.preventDefault();
					var t = $(this).parents('.mm-wp-lib-panel-form-section');
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

	</div>

	<div class="mm-submit">
		<?php
		submit_button('Save');
		?>
	</div>


</form>

