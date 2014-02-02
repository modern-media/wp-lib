
/*
<div class="mm-wp-lib-term-picker">
 	<input type="hidden" class="term_id" value="34">
	 <p class="selected">
		 <strong>Selected:</strong>
		 <span class="term-name">Term Name</span>
		 (<span class="taxonomy-name">Taxonomy</span>)
	 </p>

	<p>
		<label>
			Select Term
			<input type="text" class="autocomplete widefat">
 		</label>
	</p>

</div>
 */
jQuery(document).ready(function($){
	var body = $('body');
	var ctl_selector_string = '.mm-wp-lib-term-picker';
	var find_ctl = function(sel){
		return sel.parents(ctl_selector_string);
	};

	var term_options = [];
	var tax_label, term_label;
	_.each(mm_wp_lib_term_picker_data.taxonomies, function(tax, tax_key){
		tax_label = tax.taxonomy.labels.singular_name;
		_.each(tax.terms,function(term){
			term_label = term.name;
			term_options.push({
				label: term_label + ' (' + tax_label + ')',
				value: {
					taxonomy: 	tax_key,
					term: term
				}
			});
		})
	});

	var init = window.mm_wp_lib_term_picker_init = function(ctl){
		var term_id_input = $('input.term_id', ctl);
		var tax_input = $('input.taxonomy', ctl);
		var auto = $('input.autocomplete', ctl);
		var select = $('.selected .term-name', ctl);
		auto.autocomplete({
			source: term_options,
			select: function( event, ui ){
				term_id_input.val(ui.item.value.term.term_id);
				tax_input.val(ui.item.value.taxonomy);
				select.html(ui.item.label);
				auto.val('');
				term_id_input.change();
			},
			close: function(){
				auto.val('');
			}
		});
	};

	$(ctl_selector_string).each(function(){
		init($(this));
	});
});