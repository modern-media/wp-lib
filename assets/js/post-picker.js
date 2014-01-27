/**
 * This script adds a post picker
 * functionality to elements of
 * class 'mm-wp-lib-post-picker'.
 *
 * Example Markup:
 * <div class="mm-wp-lib-post-picker">
 *		<input type="hidden" class="id" value="34">
 *		<p>
 *		    <strong>
 *		        <a class="selection" target="_blank" href="#">
 *		            <span class="none">[none selected]</span>
 *		            <span class="selected">Post Title</span>
 *		        </a>
 *		    </strong>
 *		    <a href="#" class="remove">Remove</a>
 *	    </p>
 *
 *	    <div class="list">
 *	        <div class="filters">
 *	   	        <p><label>Search: <input type="text" class="search" placeholder="search"></label></p>
 *	   	        <p><label>Post Type: <select class="post-type"></select></label></p>
 *	   	        <p><a href="#">Apply Filters</a></p>
 *	   	    </div>
 *	   	    <div class="loading"><p>Loading...</p></div>
 *	   	    <p class="no-results">No results found</p>
 *	        <div class="results">
 *	            <p>Page <span class="page-num"></span> of <span class="num-pages"></span></p>
 *	            <p><label>Jump to page: <select class="page"></select></label>
 *	            <ul></ul>
 *	        </div>
 *	    </div>
 *	</div>
 *
 *
 */
jQuery(document).ready(function($){
	var body = $('body');
	var ctl_selector_string = '.mm-wp-lib-post-picker';
	var find_ctl = function(sel){
		return sel.parents(ctl_selector_string);
	};

	var update = window.mm_wp_lib_post_picker_update = function(ctl){
		var inp = $('input.id', ctl);
		var post_id = parseInt(inp.val());
		/**
		 * Hide the list
		 */
		$('.list', ctl).hide();
		if (! isNaN(post_id) && post_id > 0){
			$('.remove', ctl).show();
			$('.selection .selected', ctl).show();
			$('.selection .none', ctl).hide();
		} else {
			$('.remove', ctl).hide();
			$('.selection .selected', ctl).hide();
			$('.selection .none', ctl).show();
		}
	};

	var select = function(ctl, item){
		var inp = $('input.id', ctl);
		var selected = $('.selection .selected', ctl);
		if (! item){
			inp.val('');
			selected.text('');
		} else {
			inp.val(item.ID);
			selected.html(item.post_title);
		}
		update(ctl);
	};
	var query_posts = function(ctl){
		var loading = $('.loading', ctl);
		var results = $('.results', ctl);
		var no_results = $('.no-results', ctl);
		var page_select = $('.page', ctl);
		var post_type_select = $('.post-type', ctl);
		var search_input = $('.search', ctl);
		loading.show();
		results.hide();
		no_results.hide();
		var paged = parseInt(page_select.val());
		if (isNaN(paged) || paged <= 0) paged = 1;
		var o = {
			action: 'mm_posts_query',
			page: paged,
			post_type: post_type_select.val(),
			s: search_input.val()
		};
		$.post(ajaxurl, o, function(response){
			var data = response.data;

			if (0 === data.posts.length){
				no_results.show();
				loading.hide();
				return;
			}
			$('ul li', results).remove();
			_.each(data.posts, function(p){
				$('ul', results).append(
					'<li class="post-item" id="post-item-' + p.ID + '">' +
						'<a href="#">' +
							p.post_title +
						'</a>' +
					'</li>'
				);
				$('#post-item-' + p.ID).data('post', p);
			});
			paged = parseInt(data.paged);
			if (isNaN(paged) || paged <= 0) paged = 1;

			$('.page-num', ctl).text(paged);
			$('.num-pages', ctl).text(data.max_num_pages);
			$('option', page_select).remove();
			for(var n = 1; n <= data.max_num_pages; n++){
				page_select.append(
					'<option value="' + n + '">' + n + '</option>'
				);
			}
			page_select.val(paged);
			results.show();
			loading.hide();

		}, 'json');

	};

	body.on('click',  ctl_selector_string + ' .post-item a', function(event){
		event.preventDefault();
		var post = $(this).parents('.post-item').data('post');
		select(find_ctl($(this)), post);
	});

	body.on('click',  ctl_selector_string + ' .remove', function(event){
		event.preventDefault();
		select(find_ctl($(this)), false);
	});

	body.on('click',  ctl_selector_string + ' .choose', function(event){
		event.preventDefault();
		var ctl = find_ctl($(this));
		$('.list', ctl).show();
		query_posts(ctl);
	});

	body.on('change',  ctl_selector_string + ' .search', function(event){
		query_posts(find_ctl($(this)));
	});
	body.on('change',  ctl_selector_string + ' .post-type', function(event){
		query_posts(find_ctl($(this)));
	});

	body.on('keypress',  ctl_selector_string + ' .search', function(event){
		if (13 == event.keyCode){
			event.preventDefault();
			query_posts(find_ctl($(this)));
		}

	});

	$(ctl_selector_string).each(function(){
		update($(this));
	});


});