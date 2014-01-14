<?php wp_nonce_field("mmcarousel_items_nonce", "mmcarousel_items_nonce");?>
<p>
	<label>Add Item:</label>
	<select id="carousel-available-items">
		<option value="0">Select carousel item...</option>
	</select>
	<button id="carousel-available-item-add" type="button"  disabled="disabled">Add</button>
</p>



<ul id="carousel-item-list"></ul>

<ul id="carousel-item-bullpen" style="display: none;">
	<li>
		<table class="widefat">
			<tbody>
			<tr>
				<td class="img-ctr" style="width: 150px;"></td>
				<td class="title-ctr"></td>
				<td style="width: 100px; text-align: right;">
					<input type="hidden"/>
					<button type="button">Remove</button>
				</td>
			</tr>
			</tbody>
		</table>

	</li>
</ul>

<script type="text/javascript">
	var mm_carousel_items_included_ids = <?php echo json_encode($meta)?>;
	var mm_carousel_items_all = <?php echo json_encode($items)?>;
	jQuery(document).ready(function(){
		var n;
		for(n = 0; n < mm_carousel_items_all.length; n++){
			var p = mm_carousel_items_all[n];
			var opt = document.createElement("OPTION");
			opt.value = p.ID;
			opt.innerHTML = p.post_title;

			jQuery("#carousel-available-items").append(opt);
		}
		for(n = 0; n < mm_carousel_items_included_ids.length; n++){
			var id = mm_carousel_items_included_ids[n];
			var p = mm_carousel_find_item(id);
			if (p) mm_carousel_add_item(p);
		}

		jQuery("#carousel-available-item-add").click(function(){
			var id = jQuery("#carousel-available-items").val();
			if (id == 0) return;
			var p = mm_carousel_find_item(id);
			if (p) mm_carousel_add_item(p);
			jQuery("#carousel-available-items").val("0");
		});

		jQuery("#carousel-available-items").change(function(){
			if (jQuery(this).val() != 0){
				jQuery("#carousel-available-item-add").removeAttr("disabled");
			} else {
				jQuery("#carousel-available-item-add").attr("disabled", "disabled");
			}
		});
		jQuery("#carousel-item-list").sortable({stop: mm_carousel_update_form});
	});

	function mm_carousel_add_item(p){
		var li = jQuery("#carousel-item-bullpen li").clone();
		jQuery("#carousel-item-list").append(li);
		li = jQuery("#carousel-item-list li:last");
		jQuery(".title-ctr", li).html(p.post_title);
		jQuery("input", li).val(p.ID);

		if (p.thumb_url.length > 0){
			var img = document.createElement("IMAGE");
			img.src= p.thumb_url;
			jQuery(".img-ctr", li).append(img);
		} else {
			jQuery(".img-ctr", li).html("no image for this item");
		}
		jQuery("button", li).click(function(){
			jQuery(this).parents("li:first").remove();
			mm_carousel_update_form();
		});
		mm_carousel_update_form();

	}
	function mm_carousel_update_form(){
		jQuery("#carousel-item-list li").each(function(i){
			jQuery("input", this).attr("name", "mmcarousel_items[" + i + "]");
		});
	}
	function mm_carousel_find_item(id){
		for(var n = 0; n < mm_carousel_items_all.length; n++){
			var p = mm_carousel_items_all[n];
			if (p.ID == id) return p;
		}
		return false;
	}

</script>