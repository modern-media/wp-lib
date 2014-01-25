jQuery(document).ready(function($){

	var body = $('body');
	var find_ctl = function(sel){
		return sel.parents('.uploader');
	};
	var update = function(ctl){
		var input = $('input', ctl);
		var preview = $('.preview', ctl);
		var remove = $('.remove', ctl);
		var image_id = parseInt(input.val());
		if (! isNaN(image_id) && image_id > 0){
			var o = {
				action: 'mm_image_src_query',
				size: ctl.data('preview-size'),
				image_id: image_id
			};
			$.post(ajaxurl, o, function(data){
				preview.html('<img>');
				$('img', preview).attr('src', data.data[0]);
				remove.show();
			}, 'json');
		} else {
			remove.hide();
			preview.html('');
		}
	};

	body.on('click', '.uploader .choose', function(){
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

	body.on('click', '.uploader .remove', function(evt){
		evt.preventDefault();
		var ctl = find_ctl($(this));
		var input = $('input', ctl);
		input.val('');
		update(ctl);

	});

	$('.uploader').each(function(){
		update($(this));
	});
});
