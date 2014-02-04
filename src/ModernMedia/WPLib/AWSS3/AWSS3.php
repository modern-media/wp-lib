<?php
namespace ModernMedia\WPLib\AWSS3;
use Aws\S3\S3Client;
use ModernMedia\WPLib\AWSS3\Admin\Panel\SettingsPanel;
use ModernMedia\WPLib\AWSS3\Data\AWSOptions;

/**
 * Class AWSS3
 *
 * @package ModernMedia\WPLib\AWSS3
 *
 */
class AWSS3{

	const PMK_S3 = 'mm-wp-lib-aws-s3';


	const OK_AWS_KEYS = 'modern_media_aws_keys';
	/**
	 * @var AWSS3
	 */
	private static $instance;



	/**
	 * @return AWSS3
	 */
	public static function inst(){
		if (! self::$instance instanceof AWSS3){
			self::$instance = new AWSS3;
		}
		return self::$instance;
	}

	private function __construct(){

		if (is_admin()){
			new SettingsPanel;
		}
		add_filter('wp_update_attachment_metadata', array($this, '_filter_wp_update_attachment_metadata'), 10, 2);
		add_filter( 'wp_get_attachment_url', array( $this, '_filter_wp_get_attachment_url' ), 9, 2 );
		add_action( 'delete_attachment', array( $this, '_action_delete_attachment' ), 20 );
	}


	/**
	 * @param $dir
	 * @param $upload_path
	 * @param $attached
	 * @param S3Client $client
	 * @param AWSOptions $options
	 */
	private function replicate_and_delete_recursive($dir, &$upload_path, &$attached, &$client, &$options){

		$dh = dir($dir);
		while(false !== ($entry = $dh->read())){
			if ('.' == $entry || '..' == $entry) continue;
			$child_path = $dir . DIRECTORY_SEPARATOR . $entry;
			if (is_dir($child_path)){
				$this->replicate_and_delete_recursive($child_path, $upload_path, $attached, $client, $options);
			} else {

				$key = trim(str_replace(WP_CONTENT_DIR, '', $child_path), '/');
				if(isset($attached[$key])){


				}

			}

		}
		if ($dir != $upload_path){
			rmdir($dir);
		}
	}

	/**
	 * @param $data
	 * @param $post_id
	 * @return mixed
	 */
	public function _filter_wp_update_attachment_metadata($data, $post_id){
		$attached = get_post_meta($post_id, '_wp_attached_file', true);
		if (! $attached) return $data;
		$upload_path = wp_upload_dir();
		$upload_path = $upload_path['basedir'];
		$source_path = $upload_path . '/' . $attached;
		$source_dir = dirname($source_path);
		$key = trim(str_replace(WP_CONTENT_DIR, '', $upload_path), '/') . '/' . $attached;
		$key_dir = dirname($key);
		$client = $this->get_client();
		$options = $this->get_options();
		//$source = ;
		$meta = array(
			'Bucket' => $options->bucket,
			'Key' => $key,
			'sizes' => array(),
		);

		try{
			$result = $client->putObject(array(
				'Bucket'     => $options->bucket,
				'Key'        => $key,
				'SourceFile' => $source_path,
				'ACL'        => 'public-read',
			));
			/**@var \Guzzle\Service\Resource\Model $result**/
			$meta['url'] = $result->get('ObjectURL');
			$mime = get_post_mime_type($post_id);
			unlink($source_path);
			if (0 === strpos($mime, 'image/')){
				foreach($data['sizes'] as $size => $info){
					$key = $key_dir . '/' . $info['file'];
					$src =  $source_dir . '/' . $info['file'];
					$result = $client->putObject(array(
						'Bucket'     => $options->bucket,
						'Key'        => $key,
						'SourceFile' => $src,
						'ACL'        => 'public-read'
					));
					$meta['sizes'][$size]['url'] = $result->get('ObjectURL');
					$meta['sizes'][$size]['Bucket'] = $options->bucket;
					$meta['sizes'][$size]['Key'] = $key;
					unlink($src);
				}

			}
		} catch(\Exception $e){
			return $data;
		}

		update_post_meta($post_id, self::PMK_S3, $meta);
		return $data;
	}
	public function _filter_wp_get_attachment_url($url, $post_id ){
		$meta = get_post_meta($post_id, self::PMK_S3, true);
		if (is_array($meta)) return $meta['url'];
		return $url;
	}

	/**
	 * @param $post_id
	 */
	public function _action_delete_attachment($post_id){
		$meta = get_post_meta($post_id, self::PMK_S3, true);
		if (is_array($meta)){

			$client = $this->get_client();
			if ($client){
				/** @var S3Client $client */
				try{
					$client->deleteObject(array(
						'Bucket' => $meta['Bucket'],
						'Key' => $meta['Key']
					));

					foreach($meta['sizes'] as  $info){
						$client->deleteObject(array(
							'Bucket' => $info['Bucket'],
							'Key'    => $info['Key']
						));
					}
				} catch (\Exception $e){
					//fail silently?
				}
			}
		}
	}

	/**
	 * @param $arr
	 */
	public function set_option_aws($arr){
		$o = new AWSOptions($arr);
		update_option(self::OK_AWS_KEYS, $o);
	}

	/**
	 * @return bool
	 */
	public function is_option_aws_keys_valid(){
		$opts = $this->get_options();
		return ! empty($opts->id) && ! empty($opts->secret);
	}

	/**
	 * @return AWSOptions
	 */
	public function get_options(){
		$o = get_option(self::OK_AWS_KEYS);
		if (! $o instanceof AWSOptions){
			$o = new AWSOptions;
		}
		return $o;
	}

	/**
	 * @return S3Client
	 */
	public function get_client(){
		$keys = $this->get_options();
		$client = S3Client::factory(array(
			'key'    => $keys->id,
			'secret' => $keys->secret
		));
		return $client;
	}
} 