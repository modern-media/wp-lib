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
	<table class="form-table">
		<tbody>
		<tr>
			<th scope="row">
				<label for="id"><?php _e('Access Key ID')?></label>
			</th>
			<td>
				<input
					class="regular-text"
					type="text"
					name="id"
					id="id"
					value="<?php echo esc_attr($keys->id)?>"
					>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="secret"><?php _e('Access Key Secret')?></label>
			</th>
			<td>
				<input
					class="regular-text"
					type="text"
					name="secret"
					id="secret"
					value="<?php echo esc_attr($keys->secret)?>"
					>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="bucket"><?php _e('Bucket')?></label>
			</th>
			<td>
				<?php
				$error = '';
				$buckets = array();
				if (! $plugin->is_option_aws_keys_valid()){
					$error = __('Invalid or empty keys.');
				} else{
					$client = $plugin->get_client();
					if (! $client){
						$error = __('Could not create an S3 client with those keys.');
					} else {
						try {
							$data = $client->listBuckets();
							/** @var \Guzzle\Service\Resource\Model $data */
							$buckets = $data->getAll(array('Buckets'));
							$buckets = $buckets['Buckets'];
							if (! count($buckets)){
								$error = __('No buckets available.');
							}
						} catch (\Exception $e) {
							$error = sprintf(
								__('There was an error retrieving yor buckets. AWS said: %s'),
								$e->getMessage()
							);
						}

					}
				}
				if (empty($error)){
					printf(
						'<select name="bucket" id="bucket"><option value="">%s</option>',
						__('Select a bucket...')
					);
					foreach($buckets as $bucket){
						printf(
							'<option value="%s"%s>%s</option>',
							esc_attr($bucket['Name']),
							$bucket['Name'] == $keys->bucket ? ' selected="selected"' : '',
							$bucket['Name']
						);
					}
					echo '</select>';
				} else {
					printf('<p>%s</p>', $error);
				}


				?>
			</td>
		</tr>
		</tbody>
	</table>
	<?php submit_button(__('Save Changes'));?>
</form>


 