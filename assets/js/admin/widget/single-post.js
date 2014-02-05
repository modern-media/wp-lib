jQuery(document).ready(function($){
	var body = $('body');
	var ctl_selector_string = '.mm-wp-lib-single-post-widget';
	var find_ctl = function(sel){
		return sel.parents(ctl_selector_string);
	};
	var update_link_image = function(ctl){
		if ($('input.link_image', ctl).is(':checked')){
			$('.image_link_attributes', ctl).slideDown('fast');
		} else {
			$('.image_link_attributes', ctl).slideUp('fast');
		}
	};
	var update_link_title = function(ctl){
		if ($('input.link_title', ctl).is(':checked')){
			$('.title_link_attributes', ctl).slideDown('fast');
		} else {
			$('.title_link_attributes', ctl).slideUp('fast');
		}
	};
	var update_read_button = function(ctl){

		if ($('input.include_read_button', ctl).is(':checked')){
			$('.read_button_details', ctl).slideDown('fast');
		} else {
			$('.read_button_details', ctl).slideUp('fast');
		}
	};
	var update_featured_tag = function(ctl){
		if ($('input.include_feature_tag', ctl).is(':checked')){
			$('.feature-tag-ctr', ctl).slideDown('fast');
		} else {
			$('.feature-tag-ctr', ctl).slideUp('fast');
		}
	};
	var update_img_sel = function(ctl){
		var sel = $('select.image-display', ctl);
		var image_details = $('.image-details', ctl);
		var custom_img = $('.custom-image-ctr', image_details);
		var featured_img = $('.featured-image-ctr', image_details);
		if ('none' == sel.val()){
			image_details.slideUp('fast');
		} else {
			custom_img.hide();
			featured_img.hide();
			image_details.show();
			if ('custom' == sel.val()){
				custom_img.slideDown('fast');
			} else {
				featured_img.slideDown('fast');
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
			}
		}
	};
	var update_included = function(ctl){
		$('.element-list.unused li input', ctl).each(function(){
			$(this).removeAttr('name');
			$('.element-' + $(this).val()).hide();
		});
		var fn = $('.element-list-ctr', ctl).data('form-name');
		$('.element-list.used li input', ctl).each(function(i){
			$(this).attr('name', fn + '[' + i + ']');
			$('.element-' + $(this).val()).show();
		});
	};
	var update = window.mm_wp_lib_widget_single_post_update = function(ctl){
		update_link_image(ctl);
		update_link_title(ctl);
		update_read_button(ctl);
		update_img_sel(ctl);
		update_included(ctl);
		update_featured_tag(ctl);
		var lists = $('.element-list', ctl);
		lists.sortable({
			connectWith: '#' + ctl.attr('id') + ' .element-list',
			stop: function(){
				update_included(ctl);
			}
		});
	};
	var update_all = function(){
		$(ctl_selector_string).each(function(){
			update($(this));
		});
	};
	body.on('change', ctl_selector_string + ' .mm-wp-lib-post-picker input.id', function(){
		var ctl = find_ctl($(this));
		var picker = $('.mm-wp-lib-post-picker', ctl);
		if (picker.data('post')){
			$('input.title-text', ctl).val(picker.data('post').post_title);
			$('textarea.excerpt', ctl).val(picker.data('post').post_excerpt);
		} else {
			$('input.title-text', ctl).val('');
			$('textarea.excerpt', ctl).val('');
		}

	});
	body.on('change', ctl_selector_string + ' select.image-display', function(){
		update_img_sel(find_ctl($(this)));
	});
	body.on('change', ctl_selector_string + ' input.link_image', function(){
		update_link_image(find_ctl($(this)));
	});
	body.on('change', ctl_selector_string + ' input.link_title', function(){
		update_link_title(find_ctl($(this)));
	});
	body.on('change', ctl_selector_string + ' input.include_read_button', function(){
		update_read_button(find_ctl($(this)));
	});
	body.on('change', ctl_selector_string + ' input.include_feature_tag', function(){
		update_featured_tag(find_ctl($(this)));
	});
	$( ".selector" ).on( "", function( event, ui ) {} );
	body.on('sortstop', 'div.widgets-sortables', function(event, ui){
		var ctl = $(ctl_selector_string, ui.item);
		if (ctl.length > 0){
			window.setTimeout(update_all, 1000);
		}
	});
	update_all();


});




