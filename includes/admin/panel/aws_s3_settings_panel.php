<?php
namespace ModernMedia\AWSS3;
use ModernMedia\WPLib\AWSS3\AWSS3;
use ModernMedia\WPLib\AWSS3\Admin\Panel\SettingsPanel;
/**
 * @var SettingsPanel $this
 */
$plugin = AWSS3::inst();
$keys = $plugin->get_options();

?>

<form method="post" action="<?php echo $this->get_panel_url($this->get_id())?>">
	<?php
	$this->echo_nonce();
	?>

	<?php submit_button(__('Save Changes'));?>
</form>


 