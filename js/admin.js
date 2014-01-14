

function mmmu_refresh_uploader(uploader){
	var $ = jQuery;
	var image_id = uploader.data('image-id');
	$('input', uploader).val(image_id);
	if (image_id){
		var o = {
			action: 'mmmu_uploader_get_image_thumbnail_source',
			size: uploader.data('size'),
			post_id: image_id
		};
		$.post(ajaxurl, o, function(src){
			$('a.upload', uploader).html('<img>');
			$('a.upload img', uploader).attr('src', src);
			$('a.remove', uploader).html(uploader.data('remove-button-text'));
			$('a.remove', uploader).show();
		}, 'json');
	} else {
		$('a.upload', uploader).html(uploader.data('upload-button-text'));
		$('a.remove', uploader).hide();
	}
}
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
 * Post picker functions and events....
 */
jQuery(document).ready(function($){


	var post_picker_control_count = 0;
	var body = $('body');
	var find_controls = function(child){
		return child.parents('.mmmu-post-picker-controls-ctr');
	};
	var query_posts = function(controls){

		var pagenum_select = $('.page-num', controls);
		var posts_per_page_select = $('.posts-per-page', controls);
		var post_type_select = $('.post-type', controls);
		var search_input = $('.search-input', controls);
		var list = $('.posts-list', controls);
		$('div.no_posts, >div.paging', controls).hide();
		$('div.loading', controls).show();
		var page = 1;
		if ($('option', pagenum_select).length > 0) page = pagenum_select.val();
		var o = {
			action: 'mm_post_query',
			posts_per_page: posts_per_page_select.val(),
			post_type: post_type_select.val(),
			paged: page
		};
		var s = $.trim(search_input.val());
		if (s.length > 0){
			o.s = s;
		}
		$.post(ajaxurl, o, function(data){
			var n;

			var posts = data.result.posts;

			$('li', list).remove();
			for (n in posts){
				//noinspection JSUnfilteredForInLoop
				var post = posts[n];
				list.append(
					'<li class="item post post-id-' +  post.ID + '">' +
						'<a href="#">' + post.post_title + '</a>' +
					'</li>'
				);
				$('.post-id-' + post.ID, list).data('post', post);
			}


			$('option', pagenum_select).remove();
			for (n = 1; n <= data.result.max_num_pages; n++){
				pagenum_select.append(
					'<option>' + n + '</option>'
				);
			}

			pagenum_select.val(parseInt(data.result.query_vars.paged));
			$('.num-pages', controls).text(data.result.max_num_pages);

			//noinspection JSUnresolvedVariable
			if (data.result.query_vars.paged >= data.result.max_num_pages){
				$('.next', controls).attr('disabled', 'disabled');
			} else {
				$('.next', controls).removeAttr('disabled');
			}

			if (data.result.query_vars.paged <= 1){
				$('.prev', controls).attr('disabled', 'disabled');
			} else {
				$('.prev', controls).removeAttr('disabled');
			}

			$('div.loading', controls).hide();
			if (posts.length > 0){
				$('div.no-posts', controls).hide();
				$('div.paging', controls).show();

			} else {
				$('div.no-posts', controls).show();
				$('div.paging', controls).hide();
			}


		}, 'json')
	};

	body.on('click', '.mmmu-post-picker-controls a.choose', function(event){
		event.preventDefault();
		var parent = $(this).parents('.mmmu-post-picker-controls');
		var ctr = parent.data('ctr');
		if (! ctr){
			post_picker_control_count++;
			var id = 'post_picker_control_ctr_' + post_picker_control_count;
			parent.after(
				'<div class="mmmu-post-picker-controls-ctr" id="' + id +'">' +
				'	<div class="controls">' +
				'		<div class="inner">' +
				'			<div class="filters">' +
				'				<div>' +
				'					<label for="post-picker-search-' + post_picker_control_count + '">Search</label> ' +
				'					<input class="search-input" type="text" id="post-picker-search-' + post_picker_control_count + '"> ' +
				'					<a class="clear-search btn btn-xs btn-default" href="#" disabled="disabled">Clear</a>' +
				'				</div>' +
				' 				<div class="post-type-ctr">' +
				'					<label for="post-picker-post-type-' + post_picker_control_count + '">Post type</label> ' +
				'					<select class="post-type" id="post-picker-post-type-' + post_picker_control_count + '">' +
				'						<option value="any">any</option>' +
				'						</select>' +
				'				</div>' +
				'			</div>' +
				'			<div class="no-posts"><p>No posts found.</p></div>' +
				'			<div class="loading"><p>Loading posts...</p></div>' +
				'			<div class="paging">' +
				'				<a class="prev-next prev btn btn-xs btn-default" data-delta="-1" href="#">Prev</a> | ' +
				'				<label for="post-picker-page-num-' + post_picker_control_count + '">Page</label>  ' +
				'				<select class="page-num" id="post-picker-page-num-' + post_picker_control_count + '"></select> ' +
				'				of <span class="num-pages"></span> | ' +
				'				<a class="prev-next next btn btn-xs btn-default" data-delta="1" href="#">Next</a>' +
				'  				| <label for="post-picker-posts-per-page-' + post_picker_control_count + '">Posts/Page</label> ' +
				'				<select class="posts-per-page" id="post-picker-posts-per-page-' + post_picker_control_count + '">' +
				'				<option value="10" selected="selected">10</option>' +
				'				<option value="25">25</option><option value="50">50</option><option value="100">100</option>' +
				'				</select>' +
				'			</div> ' +
				'			<div class="posts">' +
				'				<ul class="posts-list"></ul>' +
				'			</div>' +
				'		</div>' +
				'	</div>' +
				'</div>'
			);
			ctr = $('#' + id);
			parent.data('ctr', ctr);
			ctr.data('parent', parent);

			var pt_select = $('select.post-type', ctr);
			for (var n in  mmmu_admin.post_types){
				pt_select.append('<option value="' + n + '">' + mmmu_admin.post_types[n] + '</option>');
			}
			var post_type = parent.data('post_type');
			if (! post_type) post_type = 'any';
			pt_select.val(post_type);
			if (post_type != 'any'){
				pt_select.attr('disabled', 'disabled');
				$('.post-type-ctr', ctr).hide();
			}
		}
		ctr.slideDown('fast', function(){
			 query_posts();
		});

	});


	body.on('click', '.mmmu-post-picker-controls a.clear', function(event){
		event.preventDefault();
		var parent = $(this).parents('.mmmu-post-picker-controls');
		$('span', parent).html('[none selected]');
		$('input', parent).val('');
	});




	body.on('click', '.mmmu-post-picker-controls-ctr li.post a', function(event){
		event.preventDefault();
		var controls = find_controls($(this));
		controls.slideUp('fast');
		var li = $(this).parents('li.post');
		var p = li.data('post');
		var parent = controls.data('parent');
		$('span', parent).html(p.post_title);
		$('input', parent).val(p.ID);

	});

	body.on('click', '.mmmu-post-picker-controls-ctr a.prev-next', function(event){
		event.preventDefault();
		var controls = find_controls($(this));
		var pagenum_select = $('.page-num', controls);
		var delta = parseInt($(this).data('delta'));
		var curr = parseInt(pagenum_select.val());
		pagenum_select.val(curr + delta);
		query_posts(controls);
	});

	body.on('change', '.mmmu-post-picker-controls-ctr select.page-num', function(){
		var controls = find_controls($(this));
		query_posts(controls);
	});

	body.on('change', '.mmmu-post-picker-controls-ctr select.posts-per-page', function(){
		var controls = find_controls($(this));
		query_posts(controls);
	});

	body.on('change', '.mmmu-post-picker-controls-ctr select.post-type', function(){
		var controls = find_controls($(this));
		query_posts(controls);
	});

	body.on('change', '.mmmu-post-picker-controls-ctr input.search-input',function(){
		var controls = find_controls($(this));
		var clear = $('.clear-search', controls);
		var s = $.trim($(this).val());
		if (s.length > 0){
			clear.removeAttr('disabled');

		} else {
			clear.attr('disabled', 'disabled');
			query_posts(controls);
		}
	});

	body.on('keypress', '.mmmu-post-picker-controls-ctr input.search-input',function(event){
		if (13 == event.keyCode){
			event.preventDefault();
		}
	});

	body.on('keyup', '.mmmu-post-picker-controls-ctr input.search-input',function(event){
		var controls = find_controls($(this));
		var clear = $('.clear-search', controls);
		var s = $.trim($(this).val());
		if (s.length > 0){
			clear.removeAttr('disabled');

		} else {

			clear.attr('disabled', 'disabled');
		}
		if (13 == event.keyCode){
			event.preventDefault();
			query_posts(controls);
		}
	});


	body.on('click', '.mmmu-post-picker-controls-ctr .clear-search', function(event){
		event.preventDefault();
		var controls = find_controls($(this));
		var inp = $('input.search-input', controls);
		var s = $.trim(inp.val());
		if (s.length > 0){
			inp.val('');
			query_posts(controls);
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
