<?php
use ModernMedia\WPLib\MetaTags\MetaTags;

?>
<p>
	<label for="<?php echo MetaTags::PMK_META_DESCRIPTION?>">
		<?php _e('Meta Description')?>
	</label>
	<input
		type="text"
		name="<?php echo MetaTags::PMK_META_DESCRIPTION?>"
		id="<?php echo MetaTags::PMK_META_DESCRIPTION?>"
		class="widefat"
		value="<?php echo esc_attr(get_post_meta($post_id, MetaTags::PMK_META_DESCRIPTION, true))?>"
	>
</p>
 