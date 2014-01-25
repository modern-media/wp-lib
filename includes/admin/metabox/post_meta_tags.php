<?php
use ModernMedia\WPLib\MetaTags\MetaTags;
$data = MetaTags::inst()->get_post_meta($post_id);
?>
<p>
	<label for="<?php echo MetaTags::PMK_META_TAGS?>_meta_description">
		<?php _e('Meta Description')?>
	</label>
	<input
		type="text"
		name="<?php echo MetaTags::PMK_META_TAGS?>[meta_description]"
		id="<?php echo MetaTags::PMK_META_TAGS?>_meta_description"
		class="widefat"
		value="<?php echo esc_attr($data->meta_description)?>"
	>
</p>
<p>
	<?php _e('Characters:')?> <span class="char-count" data-target="#<?php echo MetaTags::PMK_META_TAGS?>_meta_description"></span>
</p>
<p>
	<label for="<?php echo MetaTags::PMK_META_TAGS?>_og_description">
		<?php _e('Open Graph Description')?>
	</label>
	<input
		type="text"
		name="<?php echo MetaTags::PMK_META_TAGS?>[og_description]"
		id="<?php echo MetaTags::PMK_META_TAGS?>_og_description"
		class="widefat"
		value="<?php echo esc_attr($data->og_description)?>"
		>
</p>
<p>
	<?php _e('Characters:')?> <span class="char-count" data-target="#<?php echo MetaTags::PMK_META_TAGS?>_og_description"></span>
</p>
<p>
	<strong><?php _e('Open Graph Image')?></strong>
</p>
<div
	class="mm-wp-lib-uploader"
	data-label="<?php _e('Choose Open Graph Image')?>"
	data-preview-size="medium"
	>
	<input type="hidden" name="<?php echo MetaTags::PMK_META_TAGS?>[og_image_id]" value="<?php echo $data->og_image_id?>">
	<div class="holder"></div>
	<p><a href="#" class="choose button">
			<?php _e('Upload/Choose Site Image')?>
		</a></p>
	<p><a href="#" class="remove"><?php _e('Remove Image')?></a></p>

</div>

 