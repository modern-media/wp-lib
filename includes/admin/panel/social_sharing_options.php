<?php
/**
 * @var \ModernMedia\MustUse\SocialSharing\SocialSharingOptions $options
 */
?>
<div class="wrap">
<h2>Social Sharing</h2>

<?php
if (count($errors)){
	?>
	<div class="message error">
		<p><strong>Please correct the following error<?php if (count($errors) > 1 ) echo "s";?></strong></p>
		<?php echo "<p>" . implode("</p><p>", $errors) . "</p>"?>
	</div>
<?php
} elseif (! empty($message)){
	?>
	<div class="message">
		<p><?php echo $message?></p>
	</div>
<?php
}
?>

<form method="POST">
<?php
wp_nonce_field(\ModernMedia\MustUse\SocialSharing\SocialSharing::PLUGIN_NAMESPACE);
?>
<input type="hidden" name="submitting" value="1" />

<table class="widefat">
<thead>
<tr>
	<th colspan="3">Twitter Tweet Button Defaults</th>
</tr>
</thead>
<tbody>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="twitter_include_script">
			Include Twitter Script:
		</label>
	</td>
	<td>
		<input
			name="twitter_include_script"
			id="twitter_include_script"
			value="1"
			type="checkbox"
			<?php if($options->twitter_include_script) echo 'checked';?>
			/>
	</td>
	<td>
		Check if you want to the twitter script to be included automatically in wp_footer.
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="twitter_via_screen_name">
			Twitter Via Screen Name:
		</label>
	</td>
	<td>
		<code>@</code>
		<input
			name="twitter_via_screen_name"
			id="twitter_via_screen_name"
			value="<?php echo esc_attr($options->twitter_via_screen_name)?>"
			/>
	</td>
	<td>
		The screen name that will be appended
		to the end of tweets: &quot;via @screenname&quot;.
		Leave blank if you do not want this option.
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="twitter_related_screen_name">
			Twitter Related Screen Name:
		</label>
	</td>
	<td>
		<code>@</code>
		<input
			name="twitter_related_screen_name"
			id="twitter_related_screen_name"
			value="<?php echo esc_attr($options->twitter_related_screen_name)?>"
			/>

	</td>
	<td>
		The screen name that the user will be prompted
		to follow after tweeting.
		Leave blank if you do not want this option.
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="twitter_hashtag">
			Twitter Hashtag:
		</label>
	</td>
	<td>
		<code>#</code>
		<input
			name="twitter_hashtag"
			id="twitter_hashtag"
			value="<?php echo esc_attr($options->twitter_hashtag)?>"
			/>

	</td>
	<td>
		The hashtag that will be appended
		to the tweet.
		Leave blank if you do not want this option.
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="twitter_count_box">
			Twitter Count Box Style:
		</label>
	</td>
	<td>
		<select name="twitter_count_box" id="twitter_count_box">
			<?php
			foreach(\ModernMedia\MustUse\SocialSharing\SocialSharingOptions::twitter_count_box_options() as $o){
				?>
				<option value="<?php echo $o?>"<?php if($o == $options->twitter_count_box) echo " selected=\"selected\"";?>><?php echo $o?></option>
			<?php
			}
			?>
		</select>
	</td>
	<td>
		Whether and how to display the count box.
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="twitter_button_size">
			Twitter Button Size:
		</label>
	</td>
	<td>
		<select name="twitter_button_size" id="twitter_button_size">
			<?php
			foreach(\ModernMedia\MustUse\SocialSharing\SocialSharingOptions::twitter_button_size_options() as $o){
				?>
				<option value="<?php echo $o?>"<?php if($o == $options->twitter_button_size) echo " selected=\"selected\"";?>><?php echo $o?></option>
			<?php
			}
			?>
		</select>
	</td>
	<td>
		How big the tweet button is.
	</td>
</tr>
</tbody>

<thead>
<tr>
	<th colspan="3">Google +1 Button Defaults</th>
</tr>
</thead>
<tbody>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="google_plusone_include_script">
			Include Google +1 Script:
		</label>
	</td>
	<td>
		<input
			name="google_plusone_include_script"
			id="google_plusone_include_script"
			value="1"
			type="checkbox"
			<?php if($options->google_plusone_include_script) echo 'checked';?>
			/>
	</td>
	<td>
		Check if you want to the google +1 script to be included automatically in wp_footer.
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="google_plusone_annotation">
			Google Button Annotation:
		</label>
	</td>
	<td>
		<select name="google_plusone_annotation" id="google_plusone_annotation">
			<?php
			foreach(\ModernMedia\MustUse\SocialSharing\SocialSharingOptions::get_google_plusone_annotation_options() as $o){
				?>
				<option value="<?php echo $o?>"<?php if($o == $options->google_plusone_annotation) echo " selected=\"selected\"";?>><?php echo $o?></option>
			<?php
			}
			?>
		</select>
	</td>
	<td>
		Whether and where to display the annotation (number of shares.)
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="google_plusone_size">
			Google Button Size:
		</label>
	</td>
	<td>
		<select name="google_plusone_size" id="google_plusone_size">
			<?php
			foreach(\ModernMedia\MustUse\SocialSharing\SocialSharingOptions::get_google_plusone_size_options() as $o){
				?>
				<option value="<?php echo $o?>"<?php if($o == $options->google_plusone_size) echo " selected=\"selected\"";?>><?php echo $o?></option>
			<?php
			}
			?>
		</select>
	</td>
	<td>
		How big the Google +1 button is.
	</td>
</tr>
</tbody>
<thead>
<tr>
	<th colspan="3">StumbleUpon Badge Defaults</th>
</tr>
</thead>
<tbody>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="su_badge_include_script">
			Include StumbleUpon Badge Script:
		</label>
	</td>
	<td>
		<input
			name="su_badge_include_script"
			id="su_badge_include_script"
			value="1"
			type="checkbox"
			<?php if($options->su_badge_include_script) echo 'checked';?>
			/>
	</td>
	<td>
		Check if you want to the StumbleUpon Badge script to be included automatically in wp_footer.
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="su_badge_layout">
			Badge Layout:
		</label>
	</td>
	<td>
		<select name="su_badge_layout" id="su_badge_layout">
			<?php
			foreach(\ModernMedia\MustUse\SocialSharing\SocialSharingOptions::get_su_badge_layouts() as $o){
				?>
				<option value="<?php echo $o?>"<?php if($o == $options->su_badge_layout) echo " selected=\"selected\"";?>><?php echo $o?></option>
			<?php
			}
			?>
		</select>
	</td>
	<td>
		What kind of badge do you want?  <a href="http://www.stumbleupon.com/badges/">See here</a>.
	</td>
</tr>
</tbody>
<thead>
<tr>
	<th colspan="3">Facebook Like Button Defaults</th>
</tr>
</thead>
<tbody>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="fb_include_script">
			Include Facebook Like Button Script:
		</label>
	</td>
	<td>
		<input
			name="fb_include_script"
			id="fb_include_script"
			value="1"
			type="checkbox"
			<?php if($options->fb_include_script) echo 'checked';?>
			/>
	</td>
	<td>
		Check if you want to the Facebook Like Button script to be included automatically in wp_footer.
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="fb_app_id">
			Facebook App ID:
		</label>
	</td>
	<td>
		<input
			name="fb_app_id"
			id="fb_app_id"
			value="<?php echo esc_attr($options->fb_app_id)?>"
			/>
	</td>
	<td>
		Your Facebook AppId
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="fb_layout">
			Layout:
		</label>
	</td>
	<td>
		<select name="fb_layout" id="fb_layout">
			<?php
			foreach(\ModernMedia\MustUse\SocialSharing\SocialSharingOptions::get_fb_layout_opts() as $o){
				?>
				<option value="<?php echo $o?>"<?php if($o == $options->fb_layout) echo " selected=\"selected\"";?>><?php echo $o?></option>
			<?php
			}
			?>
		</select>
	</td>
	<td>
		On a light or dark background?  <a href="http://developers.facebook.com/docs/reference/plugins/like/">See here</a>.
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="fb_colorscheme">
			Color Scheme:
		</label>
	</td>
	<td>
		<select name="fb_colorscheme" id="fb_colorscheme">
			<?php
			foreach(\ModernMedia\MustUse\SocialSharing\SocialSharingOptions::get_fb_colorschemes() as $o){
				?>
				<option value="<?php echo $o?>"<?php if($o == $options->fb_colorscheme) echo " selected=\"selected\"";?>><?php echo $o?></option>
			<?php
			}
			?>
		</select>
	</td>
	<td>
		On a light or dark background?  <a href="http://developers.facebook.com/docs/reference/plugins/like/">See here</a>.
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="fb_action">
			Action:
		</label>
	</td>
	<td>
		<select name="fb_action" id="fb_action">
			<?php
			foreach(\ModernMedia\MustUse\SocialSharing\SocialSharingOptions::get_fb_actions() as $o){
				?>
				<option value="<?php echo $o?>"<?php if($o == $options->fb_action) echo " selected=\"selected\"";?>><?php echo $o?></option>
			<?php
			}
			?>
		</select>
	</td>
	<td>
		Like or Recommend?  <a href="http://developers.facebook.com/docs/reference/plugins/like/">See here</a>.
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="fb_font">
			Font:
		</label>
	</td>
	<td>
		<select name="fb_font" id="fb_font">
			<?php
			foreach(\ModernMedia\MustUse\SocialSharing\SocialSharingOptions::get_fb_fonts() as $o){
				?>
				<option value="<?php echo $o?>"<?php if($o == $options->fb_font) echo " selected=\"selected\"";?>><?php echo $o?></option>
			<?php
			}
			?>
		</select>
	</td>
	<td>
		<a href="http://developers.facebook.com/docs/reference/plugins/like/">See here</a>.
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="fb_show_faces">
			Show Faces:
		</label>
	</td>
	<td>
		<select name="fb_show_faces" id="fb_show_faces">
			<?php
			foreach(\ModernMedia\MustUse\SocialSharing\SocialSharingOptions::get_fb_tf_opts() as $o){
				?>
				<option value="<?php echo $o?>"<?php if($o == $options->fb_show_faces) echo " selected=\"selected\"";?>><?php echo $o?></option>
			<?php
			}
			?>
		</select>
	</td>
	<td>
		<a href="http://developers.facebook.com/docs/reference/plugins/like/">See here</a>.
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="fb_send">
			Send Button:
		</label>
	</td>
	<td>
		<select name="fb_send" id="fb_send">
			<?php
			foreach(\ModernMedia\MustUse\SocialSharing\SocialSharingOptions::get_fb_tf_opts() as $o){
				?>
				<option value="<?php echo $o?>"<?php if($o == $options->fb_send) echo " selected=\"selected\"";?>><?php echo $o?></option>
			<?php
			}
			?>
		</select>
	</td>
	<td>
		<a href="http://developers.facebook.com/docs/reference/plugins/like/">See here</a>.
	</td>
</tr>
</tbody>
<thead>
<tr>
	<th colspan="3">LinkedIn Share Button Defaults</th>
</tr>
</thead>
<tbody>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="linkedin_include_script">
			Include Linkedin Script:
		</label>
	</td>
	<td>
		<input
			name="linkedin_include_script"
			id="linkedin_include_script"
			value="1"
			type="checkbox"
			<?php if($options->linkedin_include_script) echo 'checked';?>
			/>
	</td>
	<td>
		Check if you want to the Linkedin script to be included automatically in wp_footer.
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="linkedin_layout">
			Layout:
		</label>
	</td>
	<td>
		<select name="linkedin_layout" id="linkedin_layout">
			<?php
			foreach(\ModernMedia\MustUse\SocialSharing\SocialSharingOptions::get_linkedin_layout_opts() as $o){
				?>
				<option value="<?php echo $o?>"<?php if($o == $options->linkedin_layout) echo " selected=\"selected\"";?>><?php echo $o?></option>
			<?php
			}
			?>
		</select>
	</td>
	<td>
		<a href="https://developer.linkedin.com/plugins/share-plugin-generator">See here</a>.
	</td>
</tr>
</tbody>
<thead>
<tr>
	<th colspan="3">Pinterest Share Button Defaults</th>
</tr>
</thead>
<tbody>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="pinterest_include_script">
			Include Pinterest Script:
		</label>
	</td>
	<td>
		<input
			name="pinterest_include_script"
			id="pinterest_include_script"
			value="1"
			type="checkbox"
			<?php if($options->pinterest_include_script) echo 'checked';?>
			/>
	</td>
	<td>
		Check if you want to the Pinterest script to be included automatically in wp_footer.
	</td>
</tr>
<tr>
	<td style="text-align: right; font-weight: bold;">
		<label for="pinterest_layout">
			Layout:
		</label>
	</td>
	<td>
		<select name="pinterest_layout" id="pinterest_layout">
			<?php
			foreach(\ModernMedia\MustUse\SocialSharing\SocialSharingOptions::get_pinterest_layout_opts() as $o){
				?>
				<option value="<?php echo $o?>"<?php if($o == $options->pinterest_layout) echo " selected=\"selected\"";?>><?php echo $o?></option>
			<?php
			}
			?>
		</select>
	</td>
	<td>
		<a href="http://pinterest.com/about/goodies/">See here</a>.
	</td>
</tr>
</tbody>
</table>
<?php submit_button("Save")?>

</form>
</div>