<?php
namespace ModernMedia\MustUse\Admin\Panel;
use ModernMedia\MustUse\Widget\Widgets;
/**
 * @var MMMUOptions $this
 */
$this->panel_top($this->get_page_title());
$this->form_top();
?>
	<p>
		<input
			type="checkbox"
			id="<?php echo Widgets::OK_ENABLE_DATA_ICONS?>"
			name="<?php echo Widgets::OK_ENABLE_DATA_ICONS?>"
			value="1"
			<?php
			if (get_option(Widgets::OK_ENABLE_DATA_ICONS) == 1) echo ' checked="checked" '
			?>
		>
		<label for="<?php echo Widgets::OK_ENABLE_DATA_ICONS?>">Enable Single Widget Data Icons</label>
	</p>
	<ul id="theme_directory">
		<?php
		theme_directory_check_boxes(WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'themes', get_option(Widgets::OK_DATA_ICON_CSS_PATH));
		?>
	</ul>



<?php
submit_button('Save Options');
$this->form_bottom();
$this->panel_bottom();

function theme_directory_check_boxes($path, $selected){

	if (is_dir($path)){
		$dh = dir($path);
		while (false !== ($entry = $dh->read())) {
			if ($entry == '.' || $entry == '..') continue;
			$child = $path . DIRECTORY_SEPARATOR . $entry;
			theme_directory_check_boxes($child, $selected);
		}
	} else {
		if (preg_match('/.+\.css$/', basename($path))){
			printf(
				'<li><label><input type="radio" name="%s" value="%s"%s> %s</label></li>',
				Widgets::OK_DATA_ICON_CSS_PATH,
				$path,
				$path == $selected ? ' checked="checked"' : '',
				str_replace(ABSPATH, '', $path)
			);
		}

	}
}