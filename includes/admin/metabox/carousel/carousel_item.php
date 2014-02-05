<?php
use ModernMedia\WPLib\Carousel\Carousel;
use ModernMedia\WPLib\Helper\HTML;
/**
 * @var $item
 * @var $n
 */
?>
<li class="item<?php if (! $item) echo ' template'?>">
	<div class="item-header">
		<div class="right">
			<a href="#" class="remove"><i class="fa fa-minus-square"></i> <span><?php _e('Remove')?></span></a>
		</div>
		<div class="left">
			<span class="number"><?php _e('Item #')?><span><?php if ($item) echo($n +1)?></span></span>

			<span class="header"><?php echo $item ? $item->header : ''?></span>
		</div>
	</div>
	<div class="item-content">


		<div class="left">
			<p>
				<label>
					<span><?php _e('Header Text/HTML')?></span>
					<input
						class="widefat header"
						type="text"
						data-form-name="header"
						name="<?php echo ($item) ? sprintf('%s[%s][header]', Carousel::PMK_ITEMS, $n) : '' ?>"
						value="<?php echo $item ? esc_attr($item->header) : ''?>"
						>
				</label>
			</p>
			<p>
				<label>
					<span><?php _e('Content Text/HTML')?></span>
					<textarea
						class="widefat"
						data-form-name="text"
						name="<?php echo ($item) ? sprintf('%s[%s][text]', Carousel::PMK_ITEMS, $n) : '' ?>"
						><?php echo $item ? $item->text : ''?></textarea>
				</label>
			</p>
			<p>
				<label>
					<span><?php _e('Link Header and Image To')?></span>
					<input
						class="widefat"
						type="text"
						data-form-name="link"
						name="<?php echo ($item) ? sprintf('%s[%s][link]', Carousel::PMK_ITEMS, $n) : '' ?>"
						value="<?php echo $item ?  esc_attr($item->link) : ''?>"
						>
				</label>
			</p>
			<p>
				<label>
					<span><?php _e('Item Class')?></span>
					<input
						class="widefat"
						type="text"
						data-form-name="class"
						name="<?php echo ($item) ? sprintf('%s[%s][class]', Carousel::PMK_ITEMS, $n) : '' ?>"
						value="<?php echo $item ? esc_attr($item->class) : ''?>"
						>
				</label>
			</p>
		</div>

		<div class="right">
			<div class="mm-wp-lib-uploader"  data-label="<?php _e('Choose Image')?>" data-preview-size="large">
				<?php
				$name = $item ? sprintf('%s[%s][image_id]', Carousel::PMK_ITEMS, $n) : '';
				$value = $item ? $item->image_id : 0;
				echo HTML::input_hidden($name, $value, array('data-form-name' => 'image_id'));
				?>
				<div class="holder"></div>
				<p><a href="#" class="choose button"><?php _e('Upload/Choose Image')?></a></p>
				<p><a href="#" class="remove"><?php _e('Remove Image')?></a></p>
			</div>
			
		</div>
	</div>

</li>






