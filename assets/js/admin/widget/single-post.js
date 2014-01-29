jQuery(document).ready(function($){
	var body = $('body');
	var ctl_selector_string = '.mm-wp-lib-single-post-widget';
	var widget_form_selector = '.mm-wp-lib-widget-form';
	var find_ctl = function(sel){
		return sel.parents(ctl_selector_string);
	};
	body.on('change', ctl_selector_string + ' .mm-wp-lib-post-picker input.id', function(){
		var ctl = find_ctl($(this));
		var form = ctl.parents(widget_form_selector);
		var picker = $('.mm-wp-lib-post-picker', form);
		if (picker.data('post')){
			$('input.title-text', form).val(picker.data('post').post_title);
			$('textarea.excerpt', form).val(picker.data('post').post_excerpt);
		} else {
			$('input.title-text', form).val('');
			$('textarea.excerpt', form).val('');
		}

	});
});

jQuery(document).ready(function($){
	var body = $('body');
	var ctl_selector_string = '.mm-wp-lib-widget-form-section.widget-image';
	var find_ctl = function(sel){
		return sel.parents(ctl_selector_string);
	};
	var update = window.mm_wp_lib_widget_single_post_update = function(ctl){

		var sel = $('select.image-display', ctl);
		var uploader = $('.mm-wp-lib-uploader', ctl);
		var featured_img = $('.featured-image-preview', ctl);
		if ('custom' == sel.val()){
			uploader.show();
		} else {
			uploader.hide();
		}
		if ('featured' == sel.val()){
			$('.msg', featured_img).hide();
			var post_id = $('input.post-id', ctl.parents('.mm-wp-lib-widget-form')).val();
			var o = {
				action: 'mm_featured_image_query',
				post_id: post_id
			};

			$.post(ajaxurl, o, function(resp){
				if (! resp.data.has_featured_image){
					$('.msg', featured_img).html(resp.data.message).slideDown('fast');
				} else {
					$('.holder', featured_img).html('<img>');
					$('.holder img', featured_img).attr('src', resp.data.src);
				}
			}, 'json');
			featured_img.show();
		} else {
			featured_img.hide();
		}
	};
	body.on('change', ctl_selector_string + ' select.image-display', function(){
		update(find_ctl($(this)));
	});
	$(ctl_selector_string).each(function(){
		update($(this));
	});
});


