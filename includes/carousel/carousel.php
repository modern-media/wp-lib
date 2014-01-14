<?php
namespace ModernMedia\MustUse;
/**
 * @var Carousel $this
 */
static $counter = 0;
$counter++;
if (Carousel::PT_CAROUSEL != get_post_type($id)) return '';
$items = get_post_meta($id, Carousel::PMK_ITEMS , true);
if (! is_array($items) || ! count($items)) return;

$carousel_id = 'carousel-' . $counter;
$class = array('carousel slide', 'mmmu_carousel');
$extra = get_post_meta($id, Carousel::PMK_CAROUSEL_CLASS, true);
if ($extra) $class[] = $extra;

$class = implode(' ', $class);


$interval = isset($attrs['interval']) ? $attrs['interval'] : '5000';
$pause = isset($attrs['pause']) ? $attrs['pause'] : 'hover';
?>


<div id="<?php echo $carousel_id?>" class="<?php echo $class?>" data-interval="<?php echo $interval?>" data-pause="<?php echo $pause?>">
	<div class="carousel-inner">

		<?php
		foreach ($items as $n=>$item){
			$class = array('item');
			$extra = trim($item['classes']);
			if (! empty($extra))  $class[] = $extra;
			if ($n == 0) $class[] = 'active';
			$class = implode(' ', $class);
			?>

			<div class="<?php echo $class?>">

				<?php
				if (! empty($item['image_links_to'])){
					printf('<a href="%s">', $item['image_links_to']);
				}
				printf(
					'<img src="%s" alt="%s">',
					wp_get_attachment_url($item['image_id']),
					esc_attr($item['heading'])
				);

				if (! empty($item['image_links_to'])){
					printf('</a>');
				}
				?>
				<div class="carousel-caption">
					<h3><?php echo $item['heading']?></h3>
					<?php echo apply_filters('the_content', $item['text'])?>
				</div>
			</div>
		<?php

		}
		?>

	</div>
	<div class="nav">
		<a class="left carousel-control" href="#<?php echo $carousel_id?>" data-slide="prev">
			<span class="icon-prev"></span>
		</a>
		<a class="right carousel-control" href="#<?php echo $carousel_id?>" data-slide="next">
			<span class="icon-next"></span>
		</a>
		<ol class="carousel-indicators">
			<?php
			for($n = 0; $n < count($items); $n++){
				$class = $n == 0 ? ' class="active"' : '';
				printf(
					'<li data-target="#%s" data-slide-to="%s"%s></li>',
					$carousel_id,
					$n,
					$class
				);
			}
			?>
		</ol>
	</div>
</div>

