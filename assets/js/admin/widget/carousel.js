/**
 * Created by chris on 2/4/14.
 */

jQuery(document).ready(function($){
	var body = $('body');
	var ctl_selector_str = '.mm-wp-lib-carousel-widget-controls';
	var find_ctl = function(sel){
		return sel.parents(ctl_selector_str);
	};
	body.on('change', ctl_selector_str + ' .id-ctr select', function(){
		var ctl = find_ctl($(this));
		$('input.title-text', ctl).val($('.id-ctr select', ctl).find(':selected').html())
	});

});
