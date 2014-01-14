/**
 * Carousel editor functions and events...
 */
jQuery(document).ready(function($){

	var el_count = 0;
	var item_list = $('#mmmu-carousel-item-controls').find('ul');
	var items = mmmu_carousel_metabox.items;

	var add_item = function(item){
		el_count++;
		var id = 'carousel-item-' + el_count;
		item_list.append(
			'<li class="item" id="' + id + '">' +
				'<div class="item-header">Item #<span class="item-number"></span>' +
				'<div class="action bootstrapped"><a href="#" class="remove-item" title="Remove"><i class="icon-remove"></i><span class="sr-only">Remove</span></a></div></div>' +
				'<div class="item-content">'+
				'<div style="width:45%;float:right;">' +
				'<div class="mmmu-uploader"' +
				' data-size="medium" ' +
				' data-image-id="' + (item ? item.image_id : '') + '" ' +
				' data-upload-button-text="Choose Image" ' +
				' data-remove-button-text="Remove Image" ' +
				' data-uploader-frame-title="Choose Carousel Image" ' +
				' data-uploader-frame-button-text="Choose">' +
				'<p><strong>Image</strong></p>' +
				'<p><a class="upload"></a></p>' +
				'<p><a class="remove"></a></p>' +
				'<input type="hidden" class="image_id">' +
				'</div>' +
				'</div>' +
				'<div style="width:50%;float:left;">' +
				'<p><label>Heading:<br><input type="text" class="heading widefat"></label></p>' +
				'<p><label>Text:<br><textarea class="text widefat"></textarea></label></p>' +
				'<p><label>Image Links To:<br><input type="text" class="image_links_to widefat"></label></p>' +
				'<p><label>Item Classes:<br><input type="text" class="classes widefat"></label></p>' +
				'</div>' +
				'<div class="clear"></div>' +
				'</div>' +
				'</li>'
		);
		var item_li = $('#' + id);



		if (item){
			$('input.heading', item_li).val(item.heading);
			$('textarea.text', item_li).val(item.text);
			$('input.image_links_to', item_li).val(item.image_links_to);
			$('input.classes', item_li).val(item.classes);
			$('input.image_id', item_li).val(item.image_id);
		}

		mmmu_refresh_uploader($('.mmmu-uploader', item_li));
		update();

		$('#' + id + ' .remove-item').click(function(){
			$('#' + id).remove();
			update();
		});
	};

	var update = function(){
		$('.item', item_list).each(function(i){
			var fn = 'items[' + i + ']';
			$('input.item-header span',  $(this)).html((i + 1).toString());
			$('input.image_id',  $(this)).attr('name', fn + '[image_id]');
			$('input.heading',  $(this)).attr('name', fn + '[heading]');
			$('textarea.text',  $(this)).attr('name', fn + '[text]');
			$('input.image_links_to',  $(this)).attr('name', fn + '[image_links_to]');
			$('input.classes',  $(this)).attr('name', fn + '[classes]');
			$('.item-header span.item-number', $(this)).text((i + 1).toString());
		});
	};

	item_list.sortable({
		stop: update,
		handle: '.item-header'
	});

	$('#carousel-item-add').click(function(){
		add_item();
	});

	for(var i = 0; i < items.length; i++){
		add_item(items[i]);
	}
});
