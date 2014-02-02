/*
<div class="mm-wp-lib-attribute-control" data-form-name="foobar">

	<ul class="attribute-list">
		<li class="attribute template">
			<table style="width:100%"><tr>
			<td>
				<label>
					<span>Name</span>
					<input class="attribute_name" placeholder="name">
				</label>
			</td>
			<td>
				 <label>
					 <span>Value</span>
					 <input class="attribute_value" placeholder="value">
				 </label>
			</td>
			<td><a href="#" class="remove"><span>Remove</span></td>
			</tr></table>

		</li>
	</ul>
	<a href="#" class="add button">Add New</a>
</div>
 */
jQuery(document).ready(function($){
	var body = $('body');
	var counter = 0;
	var ctl_selector_string = '.mm-wp-lib-attribute-control';
	var find_ctl = function(sel){
		return sel.parents(ctl_selector_string);
	};



	var update_form_names = function(ctl){
		var base = ctl.data('form-name');
		var n = 0
		$('.attribute-list .attribute', ctl).each(function(){
			if ($(this).hasClass('template')){
				return;
			}
			$('input.attribute_name', $(this)).attr('name', base + '[' + n + '][attribute_name]');
			$('input.attribute_value', $(this)).attr('name', base + '[' + n + '][attribute_value]');
			n++;
		});

	};

	body.on('click', ctl_selector_string + ' .remove', function(evt){
		evt.preventDefault();
		var li = $(this).parents('.attribute');
		var ctl = find_ctl($(this));
		li.slideUp('fast', function(){
			li.remove();
			update_form_names(ctl);
		})
	});

	body.on('click', ctl_selector_string + ' .add', function(evt){
		evt.preventDefault();
		var ctl = find_ctl($(this));
		counter++;
		var id = 'mm-wp-lib-attribute-control-added-' + counter;
		var list = $('.attribute-list', ctl);
		list.append($('.attribute.template', list).clone());
		$('.attribute.template:last', list).attr('id', id);
		var li = $('#' + id);

		li.slideDown('fast', function(){
			li.removeClass('template');
			update_form_names(ctl);
			$('input.attribute_name', li).focus();

		});


	});
	body.on('keyup.autocomplete', ctl_selector_string + ' .attribute_name', function(){
		$(this).autocomplete({
			source: [
				'class',
				'title',
				'target',
				'style',
				'data-target'
			]
		});
	});


});
