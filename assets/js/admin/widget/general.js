jQuery(document).ready(function($){
	var body = $('body');
	var ctl_selector_string = '.mm-wp-lib-widget-form .toggleable';

	var find_ctl = function(sel){
		return sel.parents(ctl_selector_string);
	};

	var update_open_input = function(ctl){
		var form = ctl.parents('.mm-wp-lib-widget-form');
		var opened = [];
		$('.toggleable.opened', form).each(function(){
			opened.push($(this).data('section'));
		});
		$('.widget_opened_form_sections', form).val(opened.join(','));
	};

	var toggle = function(ctl){
		if (ctl.hasClass('opened')){
			ctl.removeClass('opened');
			$('.section-header i', ctl).removeClass('fa-rotate-90');

		} else {
			ctl.addClass('opened');
			$('.section-header i', ctl).addClass('fa-rotate-90');
		}
		update_open_input(ctl);
	};
	body.on('click', ctl_selector_string + ' .section-header a', function(event){
		event.preventDefault();
		toggle(find_ctl($(this)));
	});


});
