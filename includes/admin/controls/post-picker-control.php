<?php
use ModernMedia\WPLib\HTML;
/**
 * @var $form_name
 * @var $control_id
 * @var $post
 */
$post_types = get_post_types(array('public' =>true), 'objects');
?>
<div class="mm-wp-lib-post-picker" id="<?php echo $control_id?>">
	<?php
	echo HTML::input_hidden($form_name, $post ? $post->ID : 0, array('class' => 'id post-id'));
	?>

	<table style="width: 100%">
		<tr>
			<td style="width:80%; vertical-align: top;">
				<p>
					<a class="selection choose" style="font-weight: bold" target="_blank" href="#">
						<span class="none"><?php _e('[none selected]')?></span>
						<span class="selected"><?php echo $post ? get_the_title($post->ID) : ''?></span>
					</a>
				</p>
			</td>
			<td style="width: 20%; vertical-align: bottom;">
				<p><a href="#" class="choose"><?php _e('Choose')?></a>
					<br>
					<a href="#" class="remove"><?php _e('Remove')?></a></p>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php
				$search_id = $control_id . '-single-link-post-search-filter';
				$pt_id = $control_id . 'single-link-post-post-type-filter';
				?>

				<div class="list">
					<div class="filters">
						<table style="width: 100%">
							<tr>
								<td>
									<label for="<?php echo $search_id?>"><?php _e('Search:')?></label>
								</td>
								<td>
									<input
										type="text"
										class="search widefat"
										id="<?php echo $search_id?>"
										placeholder="<?php echo esc_attr( __('Search'))?>"
										>
								</td>
							</tr>
							<tr>
								<td>
									<label for="<?php echo $pt_id?>"><?php _e('Post Type:')?></label>
								</td>
								<td>
									<select class="post-type widefat" id="<?php echo $pt_id?>">
										<option value="any"><?php _e('All Post Types')?></option>
										<?php foreach($post_types as $v => $o){
											printf(
												'<option value="%s">%s</option>',
												$v, $o->labels->name
											);
										}
										?>
									</select>
								</td>
							</tr>
						</table>

					</div>
					<div class="loading"><p><?php _e('Loading...')?></p></div>
					<p class="no-results"><?php _e('No results found')?></p>
					<div class="results">
						<p>
							<?php
							printf(
								__('Page %s of %s.'),
								'<select class="page"></select>',
								'<span class="num-pages"></span>'
							)
							?>
						</p>
						<ul></ul>
					</div>
				</div>
			</td>
		</tr>
	</table>


</div>
<?php
$jq = '#' . $control_id;
?>
<script type="text/javascript">
	if (window.mm_wp_lib_post_picker_update){
		window.mm_wp_lib_post_picker_update(jQuery('<?php echo $jq?>'));
	}
</script>