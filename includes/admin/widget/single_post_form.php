<?php
namespace ModernMedia\WPLib\Widget;
/**
 * @var SinglePost $this
 * @var $instance
 */
use ModernMedia\WPLib\Utils;
$opened = isset($instance['widget_opened_form_sections']) ? explode(',', $instance['widget_opened_form_sections']) : array();

$post = get_post($instance['id']);
$post_types = get_post_types(array('public' =>true), 'objects');
$ctr_id = $this->get_field_id('mm-wp-lib-post-picker');

?>
<div class="mm-wp-lib-single-post-widget">
	<div class="mm-wp-lib-post-picker mm-wp-lib-widget-form-section" id="<?php echo $ctr_id?>">
		<?php $this->hidden_input($instance, 'id', array('class' => 'id post-id'));?>

		<p class="section-header"><?php _e('Choose Post')?></p>
		<table style="width: 100%">
			<tr>
				<td style="width:80%; vertical-align: top;">
					<p>
						<a class="selection choose" style="font-weight: bold" target="_blank" href="#">
							<span class="none"><?php _e('[none selected]')?></span>
							<span class="selected"><?php echo $post ? get_the_title($post->ID) : ''?></span>
						</a>
					</p>
				</td>
				<td style="width: 20%; vertical-align: top;">
					<p><a href="#" class="choose"><?php _e('Choose')?></a>
						<br>
						<a href="#" class="remove"><?php _e('Remove')?></a></p>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php
					$search_id = $this->get_field_id('search-filter');
					$pt_id = $this->get_field_id('post-type-filter');
					?>

					<div class="list">
						<div class="filters">
							<table style="width: 100%">
								<tr>
									<td>
										<label for="<?php echo $search_id?>"><?php _e('Search:')?></label>
									</td>
									<td>
										<input
											type="text"
											class="search widefat"
											id="<?php echo $search_id?>"
											placeholder="<?php echo esc_attr( __('Search'))?>"
											>
									</td>
								</tr>
								<tr>
									<td>
										<label for="<?php echo $pt_id?>"><?php _e('Post Type:')?></label>
									</td>
									<td>
										<select class="post-type widefat" id="<?php echo $pt_id?>">
											<option value="any"><?php _e('All Post Types')?></option>
											<?php foreach($post_types as $v => $o){
												printf(
													'<option value="%s">%s</option>',
													$v, $o->labels->name
												);
											}
											?>
										</select>
									</td>
								</tr>
							</table>

						</div>
						<div class="loading"><p>Loading...</p></div>
						<p class="no-results">No results found</p>
						<div class="results">
							<p>
								<?php
								printf(
									__('Page %s of %s.'),
									'<select class="page"></select>',
									'<span class="num-pages"></span>'
								)
								?>
							</p>
							<ul></ul>
						</div>
					</div>
				</td>
			</tr>
		</table>


	</div>

	<script type="text/javascript">
		if (window.mm_wp_lib_post_picker_update){
			window.mm_wp_lib_post_picker_update(jQuery('#<?php echo $ctr_id?>'));
		}
	</script>

	<div data-section="excerpt" class="mm-wp-lib-widget-form-section widget-excerpt toggleable<?php if(in_array('excerpt', $opened)) echo ' opened'?>" id="<?php echo $id?>">
		<p class="section-header">
			<a href="#"><i class="toggle-section fa fa-arrow-right<?php if(in_array('excerpt', $opened)) echo ' fa-rotate-90'?>"></i>
				<?php _e('Widget Text')?></a>
		</p>

		<div class="form-field">
			<div class="label">
				<label for="<?php echo $this->get_field_id('excerpt')?>">
					<?php _e('Text')?>
				</label>
			</div>
			<div class="controls">
				<?php
				$this->text_area($instance, 'excerpt', array('rows'=>'3', 'class'=>'widefat excerpt', 'placeholder'=>__('Text or HTML')))
				?>
			</div>
		</div>
		<div class="form-field single-check">
			<?php $this->checkbox_input($instance, 'include_read_button', __('Include read button.'));?>
		</div>

		<div class="form-field">
			<div class="label">
				<label for="<?php echo $this->get_field_id('read_button_text')?>">
					<?php _e('Read Button Text')?>
				</label>
			</div>

			<div class="controls">
				<?php
				$this->text_input($instance, 'read_button_text', array('class'=>'widefat'));
				?>
			</div>


		</div>
	</div>

	<?php
	$id = $this->get_field_id('single-post-image-ctr');
	?>
	<div data-section="image" class="mm-wp-lib-widget-form-section widget-image toggleable<?php if(in_array('image', $opened)) echo ' opened'?>" id="<?php echo $id?>">
		<p class="section-header">
			<a href="#"><i class="toggle-section fa fa-arrow-right<?php if(in_array('image', $opened)) echo ' fa-rotate-90'?>"></i>
			<?php _e('Widget Image')?></a>
		</p>


		<div class="form-field">
			<div class="label">
				<label for="<?php echo $this->get_field_id('image_display')?>">
					<?php _e('Display Image')?>
				</label>
			</div>
			<div class="controls">
				<?php
				$this->select($instance, 'image_display', $this->get_image_display_options(),array('class'=>'widefat image-display'));
				?>
				<div class="mm-wp-lib-uploader" style="display: none;" data-label="<?php _e('Choose Image')?>" data-preview-size="thumbnail">
					<?php $this->hidden_input($instance, 'custom_image_id')?>
					<div class="holder"></div>
					<p><a href="#" class="choose button"><?php _e('Upload/Choose Image')?></a></p>
					<p><a href="#" class="remove"><?php _e('Remove Image')?></a></p>
				</div>
				<div class="featured-image-preview">

					<p><?php _e('Featured Image')?></p>
					<p class="msg" style="display: none;"></p>
					<div class="holder"></div>
				</div>
			</div>


		</div>

		<div class="form-field">
			<div class="label">
				<label for="<?php echo $this->get_field_id('image_placement')?>">
					<?php _e('Image Placement')?>
				</label>
			</div>

			<div class="controls">
				<?php
				$this->select($instance, 'image_placement', $this->get_image_placement_options() ,array('class'=>'widefat'));
				?>
			</div>


		</div>

		<div class="form-field">
			<div class="label">
				<label for="<?php echo $this->get_field_id('image_size')?>">
					<?php _e('Image Size')?>
				</label>
			</div>

			<div class="controls">
				<?php
				$this->select($instance, 'image_size', Utils::get_image_size_options(), array('class'=>'widefat'));
				?>
			</div>

		</div>




	</div>

	<script type="text/javascript">
		if (window.mm_wp_lib_widget_single_post_update){
			window.mm_wp_lib_widget_single_post_update(jQuery('#<?php echo $id?>'));
		}
		if (window.mm_wp_lib_uploader_update){
			window.mm_wp_lib_uploader_update(jQuery('#<?php echo $id?> .mm-wp-lib-uploader'));
		}
	</script>

</div>


