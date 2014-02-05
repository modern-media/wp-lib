<?php
use ModernMedia\WPLib\Carousel\Carousel;
use ModernMedia\WPLib\Carousel\Admin\CarouselItemsMetaBox;

/**
 * @var CarouselItemsMetaBox $this
 * @var $post_id
 */
$items = Carousel::inst()->get_post_meta_items($post_id);
?>

<div class="mm-wp-lib-carousel-items-list" data-form-name="<?php echo Carousel::PMK_ITEMS?>">

	<p>
		<a href="#" class="add button"><?php _e('Add New Item')?></a>
	</p>

	<ul class="list">
		<?php
		foreach($items as $n => $item){
			require __DIR__ . '/carousel_item.php';
		}
		?>
	</ul>
	<ul class="bullpen">
		<?php
		$item = false;
		require __DIR__ . '/carousel_item.php';
		?>
	</ul>


</div>

<script type="text/javascript">
	jQuery(document).ready(function($){
		var body = $('body');
		var ctl_selector_string = '.mm-wp-lib-carousel-items-list';
		var counter = 0;
		var find_ctl = function(sel){
			return sel.parents(ctl_selector_string);
		};
		var update_form = function(ctl){
			var list = $('.list', ctl);
			var fn = ctl.data('form-name');
			$('.item', list).each(function(n){
				if ($(this).hasClass('template')) {
					return;
				}

				$('.item-header span.number span', $(this)).text((n + 1).toString());
				$('input, textarea', $(this)).each(function(){
					$(this).attr('name', fn + '[' + n.toString() + '][' + $(this).data('form-name') + ']');
				});

			});
		};

		$( ctl_selector_string + ' .list').sortable({
			stop: function(event, ui){
				update_form(find_ctl(ui.item));
			},
			handle: '.item-header'
		});

		body.on('click', ctl_selector_string + ' .remove', function(event){
			event.preventDefault();
			var li = $(this).parents('.item');
			var ctl = find_ctl($(this));
			li.slideUp('fast', function(){
				li.remove();
				update_form(ctl);
			})
		});

		body.on('click', ctl_selector_string + ' .add', function(event){
			event.preventDefault();
			var ctl = find_ctl($(this));
			var list = $('.list', ctl);
			var bp = $('.bullpen', ctl);
			list.append($('.template', bp).clone());
			var li = $('.item:last', list);
			li.slideDown('fast', function(){
				li.removeClass('template');
				window.mm_wp_lib_uploader_update($('.mm-wp-lib-uploader', li));
				update_form(ctl);
			});

		});

		body.on('keyup', ctl_selector_string + ' input.header', function(){
			var li = $(this).parents('.item');
			$('.item-header span.header', li).html($(this).val());
		});

	});
</script>
