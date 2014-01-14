<?php
/**
 * @var $post_id
 */
use ModernMedia\MustUse\SocialSharing\SocialSharing;
?>
<div class="sharebar">
	<?php
//	if (class_exists("ModernMediaSocialSharing")){
		$post = get_post($post_id);
		$permalink = get_permalink($post_id);
		$title = get_the_title($post_id);
		$options = \ModernMedia\MustUse\SocialSharing\SocialSharing::get_options();
		$twitter = get_the_author_meta("twitter", $post->post_author);
		if (! empty($twitter)){
			$rel = explode(",", $options->twitter_related_screen_name);
			$rel[] = $twitter;
			$options->twitter_related_screen_name = implode(",", $rel);
		}
		?>
		<div class="modern-media-social-sharing">
			<div class="share-button twitter">
				<?php
				echo SocialSharing::tweetButton($permalink, $title, $options);
				?>
			</div>
			<div class="share-button googleplus">
				<?php
				echo SocialSharing::googlePlusOneButton($permalink, $options)
				?>
			</div>
			<div class="share-button facebook">
				<?php
				echo SocialSharing::fbLike($permalink, $options);
				?>
			</div>
			<!--<div class="share-button">
				<?php
				//echo ModernMediaSocialSharing::stumbleUponBadge($permalink, $options);
				?>
			</div>-->
			<div class="share-button linkedin">
				<?php
				echo SocialSharing::linkedInShare($permalink, $options);
				?>
			</div>

			<?php
			/**
			if (has_post_thumbnail()) {

			$img = wp_get_attachment_image_src(get_post_thumbnail_id(),'large');
			if ($img){
			$img = $img[0];
			$excerpt = get_the_excerpt();
			?>
			<div class="share-button">
			<?php
			echo ModernMediaSocialSharing::pinterestShare($permalink, $img, $excerpt,  $options);
			?>
			</div>
			<?php
			}
			}
			 **/
			?>

		</div>

	<?php
//	}
	?>

</div>
