/**
 * This script adds the WordPress uploader
 * functionality to elements of class 'mm-wp-lib-uploader'.
 *
 * Example:
 * <div class="mm-wp-lib-uploader" data-label="Choose Image" data-preview-size="medium">
 *		<input type="hidden" name="image_id" value="34">
 *		<div class="holder"></div>
 *		<p><a href="#" class="choose button">Upload/Choose Site Image</a></p>
 *		<p><a href="#" class="remove">Remove Image</a></p>
 *	</div>
 *
 * The input element should be a hidden element with the image's post ID as the value.
 * The .holder element displays a preview of the image.
 * The .choose element pops up the WordPress Image Uploader
 * The .remove element remove the image (sets the value of the input to '').
 *
 * Data attributes:
 * 		data-label: sets the title and button text for the WordPress uploader.
 * 		data-size: sets the size of the preview image
 *
 */
jQuery(document).ready(function($){
	var body = $('body');
	var ctl_selector_string = '.mm-wp-lib-uploader';
	var find_ctl = function(sel){
		return sel.parents(ctl_selector_string);
	};
	var update = function(ctl){
		var input = $('input', ctl);
		var holder = $('.holder', ctl);
		var remove = $('.remove', ctl);
		var image_id = parseInt(input.val());
		if (! isNaN(image_id) && image_id > 0){
			var o = {
				action: 'mm_image_src_query',
				size: ctl.data('preview-size'),
				image_id: image_id
			};
			$.post(ajaxurl, o, function(data){
				holder.html('<a href="#" class="choose"><img></a>');
				$('img', holder).attr('src', data.data[0]);
				remove.show();
			}, 'json');
		} else {
			remove.hide();
			holder.html('');
		}
	};

	body.on('click', ctl_selector_string + ' .choose', function(){
		var ctl = find_ctl($(this));
		var input = $('input', ctl);
		if (ctl.data('file_frame')){
			ctl.data('file_frame').open();
			return;
		}

		var file_frame = wp.media.frames.file_frame = wp.media({
			title: ctl.data('label'),
			button: {
				text: ctl.data('label')
			},
			multiple: false  // Set to true to allow multiple files to be selected
		});

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			var attachment = file_frame.state().get('selection').first().toJSON();
			input.val(attachment.id);
			update(ctl);
		});
		ctl.data('file_frame', file_frame);
		// Finally, open the modal
		ctl.data('file_frame').open();

	});

	body.on('click', ctl_selector_string + ' .remove', function(evt){
		evt.preventDefault();
		var ctl = find_ctl($(this));
		var input = $('input', ctl);
		input.val('');
		update(ctl);

	});

	$(ctl_selector_string).each(function(){
		update($(this));
	});
});
