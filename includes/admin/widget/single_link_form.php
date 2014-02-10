<?php
namespace ModernMedia\WPLib\Widget;
use ModernMedia\WPLib\Utils;
use ModernMedia\WPLib\Admin\Controls;
/**
 * @var SingleLinkWidget $this
 * @var $instance
 */
$ctr_id = $this->get_field_id('single_link_controls');
$opened = isset($instance['widget_opened_form_sections']) ? explode(',', $instance['widget_opened_form_sections']) : array();

?>

<div class="mm-wp-lib-single-link-widget-controls" id="<?php echo $ctr_id?>">

	<div class="mm-wp-lib-widget-form-section">
		<p class="section-header"><label for="<?php echo $this->get_field_id('type')?>"><?php _e('Link Type')?></label></p>
		<div class="form-field">

			<div class="controls">
				<?php
				$this->select($instance, 'type', $this->get_type_options(), array('class' => 'select-type'));
				?>
			</div>
		</div>

		<?php
		//none...
		$type = '';
		?>
		<div class="link-details-section link-details-section-<?php echo $type?>" <?php echo $type == $instance['type'] ? '' : ' style="display:none"'?>>
			<div class="mm-wp-lib-widget-error">
				<?php _e('Select a link type.')?>
			</div>
		</div>


		<?php
		//home...
		$type = SingleLinkWidget::TYPE_HOME;
		?>
		<div class="link-details-section link-details-section-<?php echo $type?>" <?php echo $type == $instance['type'] ? '' : ' style="display:none"'?>>
			<p><?php _e('No options available.')?></p>
		</div>

		<?php
		//url...
		$type = SingleLinkWidget::TYPE_URL;
		?>
		<div class="link-details-section link-details-section-<?php echo $type?>" <?php echo $type == $instance['type'] ? '' : ' style="display:none"'?>>
			<div class="form-field">
				<div class="label">
					<label for="<?php echo $this->get_field_id('url')?>">
						<?php _e('Outside URL')?>
					</label>
				</div>
				<div class="controls">
					<?php
					$this->text_input($instance, 'url', array( 'class'=>'widefat', 'placeholder'=>__('http://')))
					?>
				</div>
			</div>
		</div>

		<?php
		//url...
		$type = SingleLinkWidget::TYPE_POST_TYPE_ARCHIVE;
		$options = array_merge(
			array('post' =>get_post_type_object('post')),
			get_post_types(array('has_archive'=>true), 'objects')
		);
		foreach($options as $key=>$o){
			$options[$key] = $o->labels->name;
		}
		?>

		<div class="link-details-section link-details-section-<?php echo $type?>" <?php echo $type == $instance['type'] ? '' : ' style="display:none"'?>>
			<div class="form-field">
				<div class="label">
					<label for="<?php echo $this->get_field_id('post_type')?>">
						<?php _e('Post Type')?>
					</label>
				</div>
				<div class="controls">
					<?php
					$this->select($instance, 'post_type',$options,array('class' => 'widefat post_type'))
					?>
				</div>
			</div>
		</div>

		<?php
		//term archives...
		$type = SingleLinkWidget::TYPE_TERM_ARCHIVE;
		$control_id = $this->get_field_id('mm-wp-lib-single-link-term-picker');
		?>
		<div class="link-details-section link-details-section-<?php echo $type?>" <?php echo $type == $instance['type'] ? '' : ' style="display:none"'?>>
			<div class="mm-wp-lib-term-picker" id="<?php echo $control_id?>">
				<?php $this->hidden_input($instance, 'term_id', array('class'=>'term_id'));?>
				<?php $this->hidden_input($instance, 'taxonomy', array('class'=>'taxonomy'));?>
				<?php
				$taxonomy = get_taxonomy($instance['taxonomy']);
				$term = get_term($instance['term_id'], $instance['taxonomy']);
				if (is_wp_error($term)){
					$label = __('[none]');
				} else {
					$label = sprintf(
						'%s (%s)',
						$term->name,
						$taxonomy->labels->singular_name
					);
				}
				?>
				<p class="selected">
					<strong><?php _e('Selected:')?></strong>
					<span class="term-name"><?php echo $label?></span>
				</p>

				<p>
					<label>
						<?php _e('Select Term')?>
						<input type="text" class="autocomplete widefat">
					</label>
				</p>

			</div>
			<script type="text/javascript">
				if (window.mm_wp_lib_term_picker_init){
					window.mm_wp_lib_term_picker_init(jQuery('#<?php echo $control_id?>'));
				}
			</script>
		</div>

		<?php
		//single post...
		$type = SingleLinkWidget::TYPE_POST;
		?>
		<div class="link-details-section link-details-section-<?php echo $type?>" <?php echo $type == $instance['type'] ? '' : ' style="display:none"'?>>
			<?php
			Controls::post_picker_control(
				$this->get_field_name('post_id'),
				get_post($instance['post_id']),
				$this->get_field_id('mm-wp-lib-single-link-post-picker')
			);
			?>
		</div>




		<?php
		//author archives...
		$type = SingleLinkWidget::TYPE_AUTHOR_ARCHIVE;

		$users = new \WP_User_Query(
			array(
				'orderby' => 'display_name',
				'who' => 'authors'
			)
		);
		$options = array();
		foreach($users->results as $user){
			$options[$user->ID] = $user->get('display_name');
		}
		?>
		<div class="link-details-section link-details-section-<?php echo $type?>" <?php echo $type == $instance['type'] ? '' : ' style="display:none"'?>>
			<div class="form-field">
				<div class="label">
					<label for="<?php echo $this->get_field_id('author_id')?>">
						<?php _e('Author')?>
					</label>
				</div>
				<div class="controls">
					<?php
					$this->select($instance, 'author_id',$options,array('class' => 'widefat author_id'))
					?>
				</div>
			</div>
		</div>

		<?php
		//author archives...
		$type = SingleLinkWidget::TYPE_JAVASCRIPT_VOID;


		?>
		<div class="link-details-section link-details-section-<?php echo $type?>" <?php echo $type == $instance['type'] ? '' : ' style="display:none"'?>>
			<p><?php _e('No options available.')?></p>
		</div>

		<?php
		//author archives...
		$type = SingleLinkWidget::TYPE_RSS;


		?>
		<div class="link-details-section link-details-section-<?php echo $type?>" <?php echo $type == $instance['type'] ? '' : ' style="display:none"'?>>
			<p><?php _e('No options available.')?></p>
		</div>

		<?php
		//author archives...
		$type = SingleLinkWidget::TYPE_HASH;

		?>
		<div class="link-details-section link-details-section-<?php echo $type?>" <?php echo $type == $instance['type'] ? '' : ' style="display:none"'?>>
			<div class="form-field">
				<div class="label">
					<label for="<?php echo $this->get_field_id('hash_id')?>">
						<?php _e('Hash ID')?>
					</label>
				</div>
				<div class="controls">
					<?php
					$this->text_input($instance, 'hash_id',array('class' => 'widefat', 'placeholder' => '#dom-id'))
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="mm-wp-lib-widget-form-section">
		<p class="section-header"><?php _e('Link Text and Title')?></p>
		<div class="form-field">
			<div class="label">
				<label for="<?php echo $this->get_field_id('title')?>">
					<?php _e('Link Text')?>
				</label>
			</div>
			<div class="controls">
				<?php
				$this->text_input($instance, 'title',array('class' => 'widefat title', 'placeholder' => 'Link Text'))
				?>
			</div>
		</div>
		<div class="form-field">
			<div class="label">
				<label for="<?php echo $this->get_field_id('title_attribute')?>">
					<?php _e('Link Title Attribute')?>
				</label>
			</div>
			<div class="controls">
				<?php
				$this->text_input($instance, 'title_attribute',array('class' => 'widefat title_attribute', 'placeholder' => 'Title Attribute'))
				?>
			</div>
		</div>
	</div>
	<div data-section="image" class="mm-wp-lib-widget-form-section widget-image toggleable<?php if(in_array('image', $opened)) echo ' opened'?>">
		<p class="section-header">
			<a href="#"><i class="toggle-section fa fa-arrow-right<?php if(in_array('image', $opened)) echo ' fa-rotate-90'?>"></i>
			<?php _e('Use Image')?></a>
		</p>
		<div class="form-field single-check">
			<?php $this->checkbox_input($instance, 'use_image', __('Use an image.'), array('class'=>'use_image'));?>
		</div>
		<div class="image-options"<?php if (! $instance['use_image']) echo ' style="display:none" ';?>>
			<div class="form-field">
				<?php
				$uploader_id = $this->get_field_id('mm-wp-lib-widget-single-link-uploader');
				?>
				<div id="<?php echo $uploader_id?>"
					class="mm-wp-lib-uploader"
					 data-label="<?php _e('Choose Image')?>"
					 data-preview-size="medium">
					<?php $this->hidden_input($instance, 'image_id')?>
					<div class="holder"></div>
					<p><a href="#" class="choose button"><?php _e('Upload/Choose Image')?></a></p>
					<p><a href="#" class="remove"><?php _e('Remove Image')?></a></p>
				</div>
				<script type="text/javascript">
				if (window.mm_wp_lib_uploader_update){
					window.mm_wp_lib_uploader_update(jQuery('#<?php echo $uploader_id?>'));
				}
				</script>
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
				<p><?php _e('Image Attributes')?></p>
				<?php
				Controls::attribute_control(
					$this->get_field_name('image_attributes'),
					$instance['image_attributes']
				);
				?>

			</div>

		</div>

	</div>

	<div data-section="link_extra" class="mm-wp-lib-widget-form-section toggleable<?php if(in_array('link_extra', $opened)) echo ' opened'?>">
		<p class="section-header">
			<a href="#"><i class="toggle-section fa fa-arrow-right<?php if(in_array('link_extra', $opened)) echo ' fa-rotate-90'?>"></i>
				<?php _e('Link Attributes')?></a>
		</p>


		<div class="form-field">
			<?php
			Controls::attribute_control(
				$this->get_field_name('link_attributes'),
				$instance['link_attributes']
			);

			?>

		</div>



	</div>

</div><!-- /.mm-single-link-controls -->


