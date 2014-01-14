<?php
namespace ModernMedia\MustUse\Widget;
use ModernMedia\WPLib\Widget\SingleLink;
/**
 * @var SingleLink $widget
 * @var $instance
 */
use ModernMedia\WPLib\Helper\HTML;
$ctr_id = $widget->get_field_id('single_link_controls');
?>

<div class="mm-single-link-controls bootstrapped" id="<?php echo $ctr_id?>">

	<div class="type-select-ctr form-group">
		<label for="<?php echo $widget->get_field_id('type')?>">Link Type:</label>
		<?php
		$widget->select($instance, 'type', $widget->get_type_options(), array('class' => 'type form-control'));
		?>
	</div>


	<div class="options">
		<?php
		//home...
		$type = SingleLink::TYPE_HOME;
		printf(
			'<div class="option-ctr %s"%s><p>No options available.</p></div>',
			$type,
			$type == $instance['type'] ? '' : ' style="display:none"'
		);


		//url...
		$type = SingleLink::TYPE_URL;
		printf(
			'<div class="option-ctr form-group %s"%s>
			<label for="%s">URL</label>
			%s</div>',
			$type,
			$type == $instance['type'] ? '' : ' style="display:none"',
			$widget->get_field_id('url'),
			$widget->text_input($instance, 'url', array('class' => 'form-control', 'placeholder' => 'http://example.com'),false)
		);



		//post type archive...
		$type = SingleLink::TYPE_POST_TYPE_ARCHIVE;
		$options = get_post_types(array('has_archive' => true), 'objects');
		foreach($options as $key=>$o){
			$options[$key] = $o->labels->name;
		}
		printf(
			'<div class="option-ctr form-group %s"%s><p>
			<label for="%s">Post Type</label>
			%s</p></div>',
			$type,
			$type == $instance['type'] ? '' : ' style="display:none"',
			$widget->get_field_id('post_type'),
			$widget->select($instance, 'post_type',$options,array('class' => 'form-control'), false)
		);


		//term archive...
		$type = SingleLink::TYPE_TERM_ARCHIVE;
		$options = get_taxonomies(array(), 'objects');
		foreach($options as $key=>$o){
			$options[$key] = $o->labels->name;
		}
		$val = '';
		if (SingleLink::TYPE_TERM_ARCHIVE == $instance['type']){
			$term = get_term($instance['term_id'], $instance['taxonomy']);
			if ($term && ! is_wp_error($term)){
				$val = $term->name;
			}
		}
		printf(
			'<div class="option-ctr form-group %s"%s><p><label for="%s">Taxonomy</label>%s</p>
			<p><label for="%s">Term</label><input type="text" class="term_name" value="%s" id="%s" placeholder="Term Name">
			 %s</p></div>',
			$type,
			$type == $instance['type'] ? '' : ' style="display:none"',
			$widget->get_field_id('taxonomy'),
			$widget->select($instance, 'taxonomy',$options,array('class' => 'taxonomy form-control'), false),
			$widget->get_field_id('term_name'),
			esc_attr($val),
			$widget->get_field_id('term_name'),
			$widget->hidden_input($instance, 'term_id', array('class' => 'term_id form-control'), false)
		);


		?>
		<div class="form-group">
			<label>Link to Post</label>
		<?php

		//single post...
		$type = SingleLink::TYPE_POST;


		$val = '[none selected]';
		if (SingleLink::TYPE_POST == $instance['type']){
			if (empty($instance['post_id'])){
				$val = '[none selected]';
			} else {
				$val = get_the_title($instance['post_id']);
			}
		}


		printf(
			'
			<div class="option-ctr %s"%s>
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



			<div class="clear"></div>


			%s

			</div>
			</div>
			',
			$type,
			$type == $instance['type'] ? '' : ' style="display:none"',
			esc_attr('any'),
			$val,
			$widget->hidden_input($instance, 'post_id', array('class' => 'post_id'), false)

		);
		?>
		</div>
		<?php

		//author...
		$type = SingleLink::TYPE_AUTHOR_ARCHIVE;
		$val = '';
		if (SingleLink::TYPE_AUTHOR_ARCHIVE == $instance['type']){
			$val = get_the_author_meta('display_name', $instance['author_id']);
		}
		printf(
			'<div class="option-ctr form-group %s"%s>
			<p><label for="%s">Author</label>
			<input type="text" class="author_name" value="%s" id="%s" placeholder="Author Name">
			%s</p></div>',
			$type,
			$type == $instance['type'] ? '' : ' style="display:none"',
			$widget->get_field_id('author_name'),
			esc_attr($val),
			$widget->get_field_id('author_name'),
			$widget->hidden_input($instance, 'author_id', array('class' => 'author_id form-control'), false)

		);

		//rss...
		$type = SingleLink::TYPE_RSS;
		printf(
			'<div class="option-ctr %s"%s><p>No options available.</p></div>',
			$type,
			$type == $instance['type'] ? '' : ' style="display:none"'
		);
		//js void...
		$type = SingleLink::TYPE_JAVASCRIPT_VOID;
		printf(
			'<div class="option-ctr %s"%s><p>No options available.</p></div>',
			$type,
			$type == $instance['type'] ? '' : ' style="display:none"'
		);

		//hash...
		$type = SingleLink::TYPE_HASH;
		$val = '';
		if (SingleLink::TYPE_HASH == $instance['type']){
			$val = $instance['hash_id'];
		}
		printf(
			'<div class="option-ctr form-group %s"%s>
			<p><label for="%s">Element ID</label>
			%s<br><small>The href will be # plus whatever you put here. Leave blank for just #.</small></p></div>',
			$type,
			$type == $instance['type'] ? '' : ' style="display:none"',
			$widget->get_field_id('hash_id'),
			$widget->text_input($instance, 'hash_id', array('class' => 'author_id form-control'), false)

		);
		?>

		<div class="form-group">
			<label for="<?php echo $widget->get_field_id('title')?>">Link Text</label>
			<?php
			$widget->text_input($instance, 'title', array('class' => 'form-control'));
			?>
		</div>

		<div class="form-group">
			<label for="<?php echo $widget->get_field_id('link_classes')?>">Link Classes</label>
			<?php
			$widget->text_input($instance, 'link_classes', array('class' => 'form-control'));
			?>
		</div>

		<div class="form-group">
		<?php
		/** @var SingleLink $widget */

		printf(
			'<label for="%s">Extra Link Attributes</label> %s ',
			$widget->get_field_id('link_extra_attributes'),
			$widget->text_input(
				$instance,
				'link_extra_attributes',
				array('class' => 'form-control'),
				false
			)
		);
		?>
		</div>
		<?php
		$id = $widget->get_field_id('link_as_image-mmmu-uploader');
		?>
		<div
			id="<?php echo $id?>"
			class="mmmu-uploader"
			 data-size="thumbnail"
			data-image-id="<?php echo $instance['link_as_image']?>"
			data-upload-button-text="Choose Image"
			data-remove-button-text="Remove Image"
			data-uploader-frame-title="Choose Image" 
			data-uploader-frame-button-text="Choose">
			<p><strong>Use Image:</strong><br> <a class="upload"></a><br><a class="remove"></a></p>
			<input type="hidden"
				   class="image_id"
				   name="<?php echo $widget->get_field_name('link_as_image')?>"
				   id="<?php echo $widget->get_field_id('link_as_image')?>"
				   value="<?php esc_attr($instance['link_as_image'])?>"
				>
		</div>


	</div><!--/.type-options-->

</div><!-- /.mm-single-link-controls -->

<script type="text/javascript">
	if (! mmmu_single_link_controls_init){
		mmmu_uninitialized_single_link_controls.push(<?php  echo json_encode($ctr_id)?>);
	} else {
		mmmu_single_link_controls_init(<?php  echo json_encode($ctr_id)?>);
	}


	if (mmmu_refresh_uploader) {
		mmmu_refresh_uploader(jQuery('#<?php echo $id?>'));
	}

</script>