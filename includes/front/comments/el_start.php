<?php
/**
 * @var $comment
 * @var $depth
 * @var $args
 */
$comment_id = $comment->comment_ID;
?>
<div class="media" id="comment-<?php echo $comment_id?>">
	<hr class="comment-sep">

		<a class="pull-left"
		   href="<?php echo get_comment_author_url($comment_id);?>"><?php echo get_avatar($comment, $args['avatar_size']); ?></a>
		<div class="media-body">
			<h4 class="media-heading">
				<a
					class="comment-permalink pull-right"
					href="<?php echo esc_url( get_comment_link( $comment_id ) ); ?>"
					title="Link to this comment"
					><i class="fa fa-link"></i> <span class="sr-only">Link to this comment</span></a>
				<?php echo get_comment_author($comment_id) ?>
			</h4>
			<p class="text-muted comment-meta">
				<small>
					Posted on
					<?php
					/* translators: 1: date, 2: time */
					printf(
						__( '%1$s at %2$s' ),
						get_comment_date(get_option('date_format'), $comment_id),
						get_comment_date(get_option('time_format'), $comment_id) );

					if ($comment->comment_parent > 0){
						?>

						in reply to

						<a
							href="<?php echo esc_url( get_comment_link( $comment->comment_parent ) ); ?>"
							title="Link to parent comment"
							><?php echo get_comment_author($comment->comment_parent) ?> </a>
						<?php
					}
					?>

				</small>

			</p>

			<?php comment_text($comment_id)?>

			<p class="text-muted comment-meta">
				<small>
					<?php
					if ( '0' == $comment->comment_approved ){
					?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ) ?></em>

					|
					<?php
					}
					comment_reply_link(
						array_merge(
							$args,
							array(
								'add_below' => 'comment',
								'depth' => $depth + 1,
								'max_depth' => $args['max_depth'] ,
								'reply_text' => sprintf(__('Reply to %s'), get_comment_author($comment_id)),
								'login_text' => sprintf(__('Reply to %s (login required)'), get_comment_author($comment_id)),
							)
						),
						$comment
					);
					?>
				</small>

			</p>








 