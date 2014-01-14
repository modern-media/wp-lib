<?php
namespace ModernMedia\MustUse\Widget;
/**
 * @var SinglePost $widget
 * @var $instance
 */
use ModernMedia\MustUse\Helper\HTML;
$ctr_id = $widget->get_field_id('single_post_controls');
?>

<div class="mm-single-post-controls bootstrapped" id="<?php echo $ctr_id?>">


	<div class="form-group">
		<?php
		$widget->hidden_input($instance, 'title', array('class' => 'title'), true);
		$type = $widget->get_post_type();

		if ($type == 'any'){
			$label = 'Choose a Page/Post/Custom Type';
		} else {
			$types = $post_types = get_post_types(array(), 'objects');
			$otype = $types[$type];
			$label = sprintf('Choose a %s', $otype->labels->singular_name);
		}
		?>

		<label><?php echo $label?></label>
		<?php
		if (empty($instance['post_id'])){
			$val = 'Choose [none selected]';
		} else {
			$val = get_the_title($instance['post_id']);
		}

		printf(
			'
			<div
			class="mmmu-post-picker-controls"
			data-post_type="%s"
			>

			<div class="pull-right">
			<a
			href="#"
			class="choose btn btn-primary btn-xs"
			>Choose</a>

			<a
			href="#"
			class="clear btn btn-xs btn-default"
			>Clear</a>

			</div>

			<span class="post-title">%s</span>

			%s
				<div class="clear"></div>
			</div>',
			esc_attr($type),
			$val,
			$widget->hidden_input($instance, 'post_id', array('class' => 'post_id'), false)

		);
		?>
	</div>


	<div class="thumbnail-size">
		<?php
		$options = array();
		foreach(get_intermediate_image_sizes() as $key => $val){
			$options[$val] = $val;
		}
		printf(
			'<p><label for="%s">Thumbnail Size</label> %s</p>',
			$widget->get_field_id('thumbnail_size'),
			$widget->select($instance, 'thumbnail_size', $options, array(), false)
		);
		?>
	</div>
	<?php

	printf(
		'<p><label for="%s">Tag post as</label> %s <br><small>Leave blank if you don\'t want to tag</small></p>',
		$widget->get_field_id('tag_post_as'),
		$widget->text_input(
			$instance,
			'tag_post_as',
			array('class' => 'widefat'),
			false
		)
	);

	printf(
		'<p><label for="%s">Alternate Title</label> %s <br></p>',
		$widget->get_field_id('alternate_title'),
		$widget->text_input(
			$instance,
			'alternate_title',
			array('class' => 'widefat'),
			false
		)
	);

	printf(
		'<p><label for="%s">Alternate Excerpt</label> %s <br></p>',
		$widget->get_field_id('alternate_excerpt'),
		$widget->text_area(
			$instance,
			'alternate_excerpt',
			array('class' => 'widefat', 'rows' => '4'),
			false
		)
	);

	$id = $widget->get_field_id('alternate_image-mmmu-uploader');
	?>
	<div
		id="<?php echo $id?>"
		class="mmmu-uploader"
		data-size="<?php echo $instance['thumbnail_size'];?>"
		data-image-id="<?php echo $instance['alternate_image']?>"
		data-upload-button-text="Choose Image"
		data-remove-button-text="Remove Image"
		data-uploader-frame-title="Choose Image"
		data-uploader-frame-button-text="Choose">
		<p><strong>Alternate Image:</strong><br> <a class="upload"></a><br><a class="remove"></a></p>
		<input type="hidden"
			   class="image_id"
			   name="<?php echo $widget->get_field_name('alternate_image')?>"
			   id="<?php echo $widget->get_field_id('alternate_image')?>"
			   value="<?php esc_attr($instance['alternate_image'])?>"
			>
	</div>
	<?php

	printf(
		'<p>%s</p>',
		$this->checkbox_input($instance, 'include_read_button', 'Include Read Button.', array(), false)
	);
	printf(
		'<p><label for="%s">Read Button Text</label> %s </p>',
		$widget->get_field_id('read_button_text'),
		$widget->text_input(
			$instance,
			'read_button_text',
			array('class' => 'widefat'),
			false
		)
	);

	printf(
		'<p>%s</p>',
		$this->checkbox_input($instance, 'include_social', 'Include Social Media Links.', array(), false)
	);
	?>

</div><!-- /.mm-single-post-controls -->

<script type="text/javascript">

if (mmmu_refresh_uploader) {
	mmmu_refresh_uploader(jQuery('#<?php echo $id?>'));
}

</script>