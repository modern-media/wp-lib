

/**
 * Define the mmmu_single_link_controls_init function
 */

function mmmu_single_link_controls_init(ctr_id){
	var $ = jQuery;
	var ctr = $('#' + ctr_id);

	var tax_select = $('.term_archive .taxonomy', ctr);
	var term_name_input = $('.term_archive .term_name', ctr);
	var term_id_input = $('.term_archive .term_id', ctr);



	var author_name_input = $('.author_archive .author_name', ctr);
	var author_id_input = $('.author_archive .author_id', ctr);

	tax_select.change(function(){
		term_id_input.val(0);
		term_name_input.val('');
		if (tax_select.val().length > 0){
			term_name_input.autocomplete( "option", "disabled", false );
			term_name_input.focus();
		} else {
			term_name_input.autocomplete( "option", "disabled", true );
		}

	});

	term_name_input.autocomplete({
		source: function(request, response){
			var o = {
				action: 'mm_term_search',
				tax: tax_select.val(),
				search: request.term
			};

			$.post(ajaxurl, o, function(data){
				response(data.results)
			}, 'json');

		},
		select: function( event, ui ){
			term_id_input.val(ui.item.term_id);
		}
	});




	author_name_input.autocomplete({
		source: function(request, response){
			var o = {
				action: 'mm_author_search',
				search: request.term
			};

			$.post(ajaxurl, o, function(data){
				response(data.results)
			}, 'json');

		},
		select: function( event, ui ){
			author_id_input.val(ui.item.ID);
		}
	});

	$('.option-ctr', ctr).hide();
	$('.just-added', ctr).hide();
	var type = $('select.type', ctr).val();
	if (type.length > 0){
		$('.option-ctr.' + type , ctr).show();
	} else {
		$('.just-added', ctr).show();
	}

}

/**
 * Events for single link widget controls
 */
jQuery(document).ready(function($){
	var body = $('body');
	var find_controls = function(child){
		return child.parents('.mm-single-link-controls');
	};
	body.on('change', '.mm-single-link-controls select.type', function(){
		var ctr = find_controls($(this));
		$('.option-ctr', ctr).hide();
		$('.just-added', ctr).hide();
		var type = $('select.type', ctr).val();
		if (type.length > 0){
			$('.option-ctr.' + type , ctr).show();
		} else {
			$('.just-added', ctr).show();
		}
	});
});



/***
 * Image uploader functions and events....
 */
jQuery(document).ready(function($){

	var body = $('body');

	body.on('click', '.mmmu-uploader a.upload', function(event){
		event.preventDefault();
		var uploader = $(this).parents('.mmmu-uploader');
		if (uploader.data('file_frame')){
			uploader.data('file_frame').open();
			return;
		}

		var file_frame = wp.media.frames.file_frame = wp.media({
			title: uploader.data('uploader-frame-title'),
			button: {
				text: uploader.data('uploader-frame-button-text')
			},
			multiple: false  // Set to true to allow multiple files to be selected
		});

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			var attachment = file_frame.state().get('selection').first().toJSON();
			uploader.data('image-id', attachment.id);
			mmmu_refresh_uploader(uploader);
		});
		uploader.data('file_frame', file_frame);
		// Finally, open the modal
		uploader.data('file_frame').open();

	});

	body.on('click', '.mmmu-uploader a.remove', function(event){
		event.preventDefault();
		var uploader = $(this).parents('.mmmu-uploader');
		uploader.data('image-id', null);
		mmmu_refresh_uploader(uploader);
	});

});



jQuery(document).ready(function($){
	var n;
	//noinspection JSUnresolvedVariable
	for (n = 0; n < mmmu_uninitialized_single_link_controls.length; n++){
		mmmu_single_link_controls_init(mmmu_uninitialized_single_link_controls[n]);
	}


	$('.mmmu-uploader').each(function(){
		mmmu_refresh_uploader($(this));
	});
});
