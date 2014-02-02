<?php
namespace ModernMedia\WPLib\Widget;
use ModernMedia\WPLib\Utils;
use ModernMedia\WPLib\Admin\Controls;
/**
 * @var SinglePost $this
 * @var $instance
 */
$opened = isset($instance['widget_opened_form_sections']) ? explode(',', $instance['widget_opened_form_sections']) : array();
$ctr_id = $this->get_field_id('mm-wp-lib-post-picker-ctr');
?>
<div class="mm-wp-lib-single-post-widget" id="<?php echo $ctr_id?>" xmlns="http://www.w3.org/1999/html">
	<div class="mm-wp-lib-widget-form-section">
		<?php
		Controls::post_picker_control(
			$this->get_field_name('id'),
			get_post($instance['id']),
			$this->get_field_id('mm-wp-lib-single-post-post-picker')
		);
		?>
	</div>

	<div data-section="included_elements" class="mm-wp-lib-widget-form-section toggleable<?php if(in_array('included_elements', $opened)) echo ' opened'?>">
		<p class="section-header">
			<a href="#"><i class="toggle-section fa fa-arrow-right<?php if(in_array('included_elements', $opened)) echo ' fa-rotate-90'?>"></i>
				<?php _e('Included Elements')?></a>
		</p>
		<div class="form-field">
			<?php
			$elements = $this->get_element_options();
			$form_name = $this->get_field_name('included_elements');
			?>
			<table style="width: 100%;" class="element-list-ctr" data-form-name="<?php echo $form_name?>">
				<tr>
					<th style="width: 50%; vertical-align: top;"><?php _e('Available')?></th>
					<th style="width: 50%; vertical-align: top;"><?php _e('Included')?></th>
				</tr>
				<tr>
					<td style="width: 50%; vertical-align: top;">
						<ul class="element-list unused">
							<?php
							foreach($elements as $key => $label){
								if (! in_array($key, $instance['included_elements'])){
									?>
									<li>
										<input type="hidden" value="<?php echo $key?>">
										<?php echo $label?>
									</li>
									<?php
								}

							}
							?>
						</ul>
					</td>
					<td style="width: 50%;vertical-align: top;">
						<ul class="element-list used">
							<?php
							$n = 0;
							foreach($instance['included_elements'] as $key){
								$label = $elements[$key];
								?>
								<li>
									<input type="hidden" name="<?php echo $form_name?>[<?php echo $n?>]" value="<?php echo $key?>">
									<?php echo $label?>
								</li>
								<?php
								$n++;
							}
							?>

						</ul>
					</td>
				</tr>
			</table>
		</div>

	</div>
	<div data-section="title" class="mm-wp-lib-widget-form-section toggleable<?php if(in_array('title', $opened)) echo ' opened'?>">
		<p class="section-header">
			<a href="#"><i class="toggle-section fa fa-arrow-right<?php if(in_array('title', $opened)) echo ' fa-rotate-90'?>"></i>
				<?php _e('Widget Header')?></a>
		</p>

		<div class="form-field">
			<div class="label">
				<label for="<?php echo $this->get_field_id('title')?>">
					<?php _e('Header Text')?>
				</label>
			</div>
			<div class="controls">
				<?php
				$this->text_input($instance, 'title', array('class'=>'widefat title-text', 'placeholder'=>__('Header Text')));
				?>
			</div>

		</div>
		<div class="form-field single-check">
			<?php $this->checkbox_input($instance, 'display_title', __('Display header.'));?>
		</div>

		<div class="form-field single-check">
			<?php $this->checkbox_input($instance, 'link_title', __('Link header to post.'), array('class' => 'link_title'));?>
		</div>

		<div class="title_link_attributes">
			<div class="form-field">
				<div class="label">
					<?php _e('Header Link Attributes')?>
				</div>
				<div class="controls">
					<?php
					Controls::attribute_control(
						$this->get_field_name('title_link_attributes'),
						$instance['title_link_attributes']
					);
					?>
				</div>
			</div>
		</div>



	</div> <!-- .mm-wp-lib-widget-form-section -->

	<div data-section="excerpt" class="mm-wp-lib-widget-form-section widget-excerpt toggleable<?php if(in_array('excerpt', $opened)) echo ' opened'?>">
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
			<?php $this->checkbox_input($instance, 'include_read_button', __('Include read button.'), array('class'=>'include_read_button'));?>
		</div>

		<div class="read_button_details">
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

			<div class="read_button_attributes">
				<div class="form-field">
					<div class="label">
						<?php _e('Read Button Attributes')?>
					</div>
					<div class="controls">
						<?php
						Controls::attribute_control(
							$this->get_field_name('read_button_attributes'),
							$instance['read_button_attributes']
						);
						?>
					</div>
				</div>
			</div>
		</div>

	</div>


	<div data-section="image" class="mm-wp-lib-widget-form-section widget-image toggleable<?php if(in_array('image', $opened)) echo ' opened'?>">
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
			</div>
		</div>

		<div class="image-details">
			<div class="custom-image-ctr">
				<div class="form-field">
					<div class="label">
						<?php _e('Custom Image')?>
					</div>
					<div class="controls">
						<?php
						Controls::uploader_control(
							$this->get_field_id($ctr_id . '-image-uploader'),
							$this->get_field_name('custom_image_id'),
							$instance['custom_image_id'],
							__('Choose Image'),
							'medium'
						);
						?>
					</div>
				</div>
			</div>
			<div class="featured-image-ctr">
				<div class="form-field">
					<div class="label">
						<?php _e('Featured Image')?>
					</div>
					<div class="controls">
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
			<div class="form-field">
				<div class="label">
					<?php _e('Image Attributes')?>
				</div>
				<div class="controls">
					<?php
					Controls::attribute_control(
						$this->get_field_name('image_attributes'),
						$instance['image_attributes']
					);
					?>
				</div>
			</div>

			<div class="form-field single-check">
				<?php
				$this->checkbox_input($instance, 'link_image', __('Link image to post.'), array('class' => 'link_image'))
				?>
			</div>
			<div class="image_link_attributes">
				<div class="form-field">
					<div class="label">
						<?php _e('Image Link Attributes')?>
					</div>
					<div class="controls">
						<?php
						Controls::attribute_control(
							$this->get_field_name('image_link_attributes'),
							$instance['image_link_attributes']
						);
						?>
					</div>
				</div>
			</div>



		</div>


	</div>



</div>
<?php
$jq = '#' . $ctr_id;
?>
<script type="text/javascript">
	if (window.mm_wp_lib_widget_single_post_update){
		window.mm_wp_lib_widget_single_post_update(jQuery('<?php echo $jq?>'));
	}
</script>

