<?php
use ModernMedia\WPLib\MetaTags\MetaTags;
use ModernMedia\WPLib\MetaTags\Data\SiteMetaSettings;
use ModernMedia\WPLib\MetaTags\Admin\SiteMetaTagsSettingsPanel;
/**
 * @var SiteMetaTagsSettingsPanel $this
 */

$data = $this->form_data;
/**
 * @var SiteMetaSettings $data;
 */
?>

<form method="post" action="<?php echo SiteMetaTagsSettingsPanel::get_panel_url($this->get_id())?>">
	<?php
	$this->echo_nonce();
	?>

	<h3><?php _e('Default Site Meta')?></h3>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="default_site_meta_description"><?php _e('Meta Description')?></label>
			</th>
			<td>
				<input
					type="text"
					class="widefat"
					name="default_site_meta_description"
					id="default_site_meta_description"
					placeholder="<?php echo esc_attr(__('Default description of your site'))?>"
					value="<?php echo esc_attr($data->default_site_meta_description) ?>"
				>
				<p>
					<?php _e('Characters:')?> <span class="char-count" data-target="#default_site_meta_description"></span>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="default_site_og_description"><?php _e('Open Graph Description')?></label>
			</th>
			<td>
				<input
					type="text"
					class="widefat"
					name="default_site_og_description"
					id="default_site_og_description"
					placeholder="<?php echo esc_attr(__('Default og:description of your site'))?>"
					value="<?php echo esc_attr($data->default_site_og_description) ?>"
					>
				<p>
					<?php _e('Characters:')?> <span class="char-count" data-target="#default_site_og_description"></span>
				</p>
			</td>
		</tr>
	</table>

	<h3><?php _e('Facebook/Social Sharing Image Sizes')?></h3>
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
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="og_image_width">
					<?php _e('Width')?>
				</label>
			</th>
			<td>
				<input
					type="text"
					name="og_image_width"
					id="og_image_width"
					placeholder="<?php echo esc_attr(__('Recommended: ')) . MetaTags::OG_IMAGE_WIDTH?>"
					value="<?php echo esc_attr($data->og_image_width)?>"
					size="7"
				> px
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="og_image_height">
					<?php _e('Height')?>
				</label>
			</th>
			<td>
				<input
					type="text"
					name="og_image_height"
					id="og_image_height"
					placeholder="<?php echo esc_attr(__('Recommended: ')) . MetaTags::OG_IMAGE_HEIGHT?>"
					value="<?php echo esc_attr($data->og_image_height)?>"
					size="7"
					> px
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="">Default Site Image</label>
			</th>
			<td>
				<div
					class="uploader"
					data-label="<?php _e('Choose Site Image')?>"
					data-preview-size="medium"
				>
					<input type="hidden" name="default_site_og_image_id" value="<?php echo $data->default_site_og_image_id?>">
					<div class="preview"></div>
					<p><a href="#" class="choose button">
						<?php _e('Upload/Choose Site Image')?>
					</a></p>
					<p><a href="#" class="remove"><?php _e('Remove Image')?></a></p>

				</div>
			</td>

		</tr>
	</table>

	<?php submit_button(__('Save'))?>
</form>