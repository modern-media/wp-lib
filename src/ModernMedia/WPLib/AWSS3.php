<?php
namespace ModernMedia\WPLib;
use Aws\S3\S3Client;

/**
 * Class AWSS3
 *
 * @package ModernMedia\WPLib\AWSS3
 *
 */
class AWSS3{

	const PMK_S3 = 'mm-wp-lib-aws-s3';

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
		add_filter('wp_update_attachment_metadata', array($this, '_filter_wp_update_attachment_metadata'), 10, 2);
		add_filter( 'wp_get_attachment_url', array( $this, '_filter_wp_get_attachment_url' ), 9, 2 );
		add_action( 'delete_attachment', array( $this, '_action_delete_attachment' ), 20 );
	}



	public function copy_attachments_to_S3(){
		$upload_path = wp_upload_dir();
		return $upload_path;

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
		$options = WPLib::inst()->get_settings();
		//$source = ;
		$meta = array(
			'Bucket' => $options->awss3_bucket,
			'Key' => $key,
			'sizes' => array(),
		);

		try{
			$result = $client->putObject(array(
				'Bucket'     => $options->awss3_bucket,
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
						'Bucket'     => $options->awss3_bucket,
						'Key'        => $key,
						'SourceFile' => $src,
						'ACL'        => 'public-read'
					));
					$meta['sizes'][$size]['url'] = $result->get('ObjectURL');
					$meta['sizes'][$size]['Bucket'] = $options->awss3_bucket;
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
	 * @return bool
	 */
	public function is_option_aws_keys_valid(){
		$opts = WPLib::inst()->get_settings();
		return ! empty($opts->awss3_id) && ! empty($opts->awss3_secret);
	}

	/**
	 * @param $opts
	 * @param $error
	 * @return bool
	 */
	public function check_settings($opts, &$error){
		try{
			$client = S3Client::factory(array(
				'key'    => $opts->awss3_id,
				'secret' => $opts->awss3_secret
			));
			$client->putObject(array(
				'Bucket' => $opts->awss3_bucket,
				'Key'    => 'test.txt',
				'Body'   => 'Hello!'
			));
			return true;
		} catch (\Exception $e){
			$error = sprintf(__('Something is wrong. Amazon Web Services S3 said: %s'), $e->getMessage());
			return false;
		}


	}



	/**
	 * @return S3Client
	 */
	public function get_client(){
		$opts = WPLib::inst()->get_settings();
		$client = S3Client::factory(array(
			'key'    => $opts->awss3_id,
			'secret' => $opts->awss3_secret
		));
		return $client;
	}
} 