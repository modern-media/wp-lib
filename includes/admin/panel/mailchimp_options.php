<?php
namespace ModernMedia\WPLib\Admin\Panel;
/**
 * @var MMMUOptions $this
 */

use ModernMedia\MustUse\MailChimp;

$api_key = '';
$list_id = '';

if (get_option(MailChimp::OK_MAILCHIMP_API_KEY)){
	$api_key = get_option(MailChimp::OK_MAILCHIMP_API_KEY);
}
if(get_option(MailChimp::OK_MAILCHIMP_LIST_ID)){
	$list_id = get_option(MailChimp::OK_MAILCHIMP_LIST_ID);
}

$this->panel_top($this->get_page_title());
$this->form_top();
?>
	<p>
		<label for="api_key"><?php _e('API Key') ?><br />
			<input type="text" name="api_key" id="api_key" class="input" value="<?php echo esc_attr(stripslashes($api_key)); ?>" size="40" /></label>
	</p>
	<p>
		<label for="list_id"><?php _e('List ID') ?><br />
			<input type="text" name="list_id" id="list_id" class="input" value="<?php echo esc_attr(stripslashes($list_id)); ?>" size="15" /></label>
	</p>

<?php
submit_button('Save Options');
$this->form_bottom();
$this->panel_bottom();