<?php
use ModernMedia\WPLib\SocialSharing\ShareThis;
use ModernMedia\WPLib\SocialSharing\Admin\ShareThisOptionPanel;

/**
 * @var ShareThisOptionPanel $this
 */
$options = ShareThis::inst()->get_options();

?>
<form method="POST" action="<?php echo $this->get_panel_url($this->get_id())?>">
	<?php
	$this->echo_nonce();
	?>
	<table class="form-table">

		<tr>
			<th scope="row">
				<label for="publisher_key"><?php _e('Share This Publisher Key')?></label>
			</th>
			<td>
				<input
					type="text"
					class="widefat"
					id="publisher_key"
					name="publisher_key"
					value="<?php echo $options->publisher_key?>"
				>
			</td>
			<td>
				<small>
					<?php
					printf(
						__('You can get your publisher key <a href="%s" target="_blank">here</a>.'),
						'http://www.sharethis.com/account/settings_new'
					)
					?>
				</small>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="share_bar_services"><?php _e('Share Bar Services')?></label>
			</th>
			<td>
				<textarea
					id="share_bar_services"
					name="share_bar_services"
					rows="10"
					><?php echo $options->share_bar_services?></textarea>
			</td>
			<td>
				<small>
					<?php
					_e('Put one service per line, in the order you want them to appear. Services include:');
					?>
					<br>
					<?php
					echo implode('<br>', array_keys(ShareThis::inst()->get_services()));
					?>
				</small>
			</td>

		</tr>
	</table>

	<?php submit_button(__('Save Options'))?>
</form>