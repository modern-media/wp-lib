jQuery(document).ready(function($){
	var body = $('body');
	var ctl_selector_string = '.mm-wp-lib-single-link-widget-controls';
	var find_ctl = function(sel){
		return sel.parents(ctl_selector_string);
	};

	var update_title = function(ctl){

		var type = $('.select-type', ctl).val();
		var title_new = '';
		switch (type){
			case 'home':
				title_new = 'Home';
				break;
			case 'url':
				title_new = 'Link text';
				break;
			case 'post_type_archive':
				title_new = $('select.post_type option:selected', ctr).html();
				break;
			case 'term_archive':
				title_new = $('.term-name', ctl).html();
				break;
			case 'single_post':
				title_new = $('.mm-wp-lib-post-picker', ctl).data('post').post_title;
				break;
			case 'author_archive':
				title_new = $('select.author_id option:selected', ctr).html();
				break;
			case 'rss_feed':
				title_new = 'RSS';
				break;
			case 'javascript_void':
				title_new = 'Link text';
				break;
			case 'hash':
				title_new = 'Link text';
				break;

		}
		$('input.title, input.title_attribute', ctl).val(title_new);

	};
	var handle_type_select = function(ctl){
		var select = $('.select-type', ctl);
		var type = select.val();
		$('.link-details-section', ctl).hide();
		$('.link-details-section-' + type, ctl).slideDown('fast');
		update_title();
	};
	body.on('change', ctl_selector_string + ' .select-type', function(){
		handle_type_select(find_ctl($(this)));
	});
	body.on('change', ctl_selector_string + ' select.author_id', function(){
		update_title(find_ctl($(this)));
	});
	body.on('change', ctl_selector_string + ' select.post_type', function(){
		update_title(find_ctl($(this)));
	});
	body.on('change', ctl_selector_string + ' input.term_id', function(){
		update_title(find_ctl($(this)));
	});
	body.on('change', ctl_selector_string + ' input.post-id', function(){
		update_title(find_ctl($(this)));
	});
	body.on('change', ctl_selector_string + ' .use_image', function(){
		var ctr = $(this).parents('.mm-wp-lib-widget-form-section');
		var opts = $('.image-options', ctr);
		if($(this).is(':checked')){
			opts.slideDown('fast');
			window.mm_wp_lib_uploader_update($('.mm-wp-lib-uploader', ctr));
		} else {
			opts.slideUp('fast');
		}
	})
});
