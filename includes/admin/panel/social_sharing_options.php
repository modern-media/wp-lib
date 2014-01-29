<?php
namespace ModernMedia\WPLib\SocialSharing;
use ModernMedia\WPLib\Helper\HTML;
use ModernMedia\WPLib\SocialSharing\Admin\SocialSharingOptionsPanel;
/**
 * @var SocialSharingOptionsPanel $this
 */
$options = SocialSharing::inst()->get_options();
$social_sharing = SocialSharing::inst();
?>


<form method="POST" action="<?php echo $this->get_panel_url($this->get_id())?>">
<?php
$this->echo_nonce();
?>

<h3><?php _e('Tweet Button Defaults')?></h3>

<?php
$data = $options->tweet_button;
?>

<table class="mm-wp-lib-form-table">
	<tr>
		<td>
			<label for="tweet_button_url"><?php _e('Default URL')?></label>
		</td>
		<td>
			<input
				name="tweet_button[url]"
				id="tweet_button_url"
				placeholder="<?php echo esc_attr(__('http://example.com'))?>"
				value="<?php echo esc_attr($data->url)?>"
				type="text"
				class="widefat"
				>
		</td>
		<td>
			<div class="help">
				<?php _e('The default URL for the Twitter share button.');?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<label for="tweet_button_text"><?php _e('Default Tweet Text')?></label>
		</td>
		<td>
			<input
				name="tweet_button[text]"
				id="tweet_button_text"
				placeholder="<?php echo esc_attr(__('Look at this cool site!'))?>"
				value="<?php echo esc_attr($data->text)?>"
				type="text"
				class="widefat"
				>
		</td>
		<td>
			<div class="help">
				<?php _e('The default tweet text for the Twitter share button.');?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<label for="tweet_button_via"><?php _e('Via Screen Name')?></label>
		</td>
		<td>
			<input
				name="tweet_button[via]"
				id="tweet_button_via"
				placeholder="<?php echo esc_attr(__('via screen name'))?>"
				value="<?php echo esc_attr($data->via)?>"
				type="text"
				class="widefat"
			>
		</td>
		<td>
			<div class="help">
				<?php _e('The screen name that will be appended to the end of tweets: &quot;via @screenname&quot;. Leave blank if you do not want this option.');?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<label for="tweet_button_related"><?php _e('Related Screen Names')?></label>
		</td>
		<td>
			<input
				name="tweet_button[related]"
				id="tweet_button_related"
				placeholder="<?php echo esc_attr(__('related screen names'))?>"
				value="<?php echo esc_attr($data->related)?>"
				type="text"
				class="widefat"
				>
		</td>
		<td>
			<div class="help">
				<?php _e('The screen names that the user will be prompted to follow after tweeting. Separate screen names with commas. Leave blank if you do not want this option.');?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<label for="tweet_button_hashtags"><?php _e('Hashtags')?></label>
		</td>
		<td>
			<input
				name="tweet_button[hashtags]"
				id="tweet_button_hashtags"
				placeholder="<?php echo esc_attr(__('hashtags'))?>"
				value="<?php echo esc_attr($data->hashtags)?>"
				type="text"
				class="widefat"
				>
		</td>
		<td>
			<div class="help">
				<?php _e('The hashtags that will be appended to the tweet. Separate multiple hashtags with commas. Leave blank if you do not want this option.');?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<label for="tweet_button_count">
				<?php _e('Default Count Box Style')?>
			</label>
		</td>
		<td>
			<?php
			echo HTML::select(
				'tweet_button[count]',
				$social_sharing->get_twitter_count_box_options(),
				$data->count,
				array('id' => 'tweet_button_count')
			);
			?>
		</td>
		<td>
			<div class="help">
				<?php _e('Whether and how to display the count box.');?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<label for="tweet_button_counturl"><?php _e('Default Count URL')?></label>
		</td>
		<td>
			<input
				name="tweet_button[counturl]"
				id="tweet_button_counturl"
				placeholder="<?php echo esc_attr(__('http://example.com'))?>"
				value="<?php echo esc_attr($data->counturl)?>"
				type="text"
				class="widefat"
				>
		</td>
		<td>
			<div class="help">
				<?php _e('If you are using a shortened URL for the default tweet URL above, put the canonical URL here, in order for tweet counts to be properly attributed.');?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<label for="tweet_button_size">
				<?php _e('Default Button Size')?>
			</label>
		</td>
		<td>
			<?php
			echo HTML::select(
				'tweet_button[size]',
				$social_sharing->get_twitter_button_size_options(),
				$data->size,
				array('id' => 'tweet_button_size')
			);
			?>
		</td>
		<td>
			<div class="help">
				<?php _e('The size of the button.');?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<label for="tweet_button_lang">
				<?php _e('Default Language')?>
			</label>
		</td>
		<td>
			<?php
			echo HTML::input_text(
				'tweet_button[lang]',
				$data->lang,
				array('id' => 'tweet_button_lang', 'placeholder' => 'en', 'size' => '2')
			);
			?>
		</td>
		<td>
			<div class="help">
				<?php _e('The two character language code, if you want the tweet button to be rendered in a language other than English.');?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<label for="tweet_button_dnt">
				<?php _e('Tailoring Twitter')?>
			</label>
		</td>
		<td>

			<?php
			echo HTML::input_check(
				'tweet_button[dnt]',
				'1',
				1 == $data->dnt,
				array('id' => 'tweet_button_dnt')
			);
			?>
			<label for="tweet_button_dnt"><?php _e('Opt-out of tailoring Twitter')?></label>
		</td>
		<td>
			<div class="help">
				<?php _e('Twitter tailors content and suggestions for Twitter users. Check the box to opt-out of this behavior.');?>
			</div>
		</td>
	</tr>
</table>

<h3><?php _e('Google Plus')?></h3>

	<table class="form-table mm-wp-lib-form-table">
		<tr>
			<td>
				<label for="google_plus_size"><?php _e('Google Plus Button Size')?></label>
			</td>
			<td>
				<?php
				echo HTML::radios(
					'google_plus_size',
					$social_sharing->get_google_plusone_size_options(),
					$options->google_plus_size,
					array('id' => 'google_plus_size')
				);
				?>
			</td>
		<td>
			<div class="help">
					<?php _e('Help TKTK');?>
				</div>
			</td>
		</tr>
</table>


<?php submit_button("Save")?>

</form>
