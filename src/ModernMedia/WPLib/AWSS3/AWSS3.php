<?php
namespace ModernMedia\WPLib\AWSS3;
use Aws\S3\S3Client;
use ModernMedia\WPLib\AWSS3\Admin\Panel\SettingsPanel;
use ModernMedia\WPLib\AWSS3\Data\AWSOptions;

class AWSS3{

	const OK_AWS_KEYS = 'modern_media_aws_keys';
	/**
	 * @var AWSS3
	 */
	private static $instance;

	private $settings_panel;

	/**
	 * @var S3Client
	 */
	private $client = null;

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
		$this->settings_panel = new SettingsPanel;
		add_filter('wp_generate_attachment_metadata', array($this, '_filter_wp_generate_attachment_metadata'), 10, 2);
		add_filter( 'wp_get_attachment_url', array( $this, '_filter_wp_get_attachment_url' ), 9, 2 );
		add_action( 'delete_attachment', array( $this, '_action_delete_attachment' ), 20 );
	}

	public function _filter_wp_get_attachment_url($url, $post_id ){
		$meta = wp_get_attachment_metadata($post_id);
		if (isset($meta['s3_url'])) return $meta['s3_url'];
		return $url;
	}

	public function _action_delete_attachment($post_id){
		$meta = wp_get_attachment_metadata($post_id);
		if (isset($meta['s3_url'])){
			$mime = get_post_mime_type($post_id);
			$parts = explode('/', $mime);
			$is_image = 'image' == array_shift($parts);

			if (! isset($meta['s3_bucket']) || isset($meta['s3_key'])) return;


			$client = $this->get_client();
			$result = $client->deleteObject(array(
				'Bucket'     => $meta['s3_bucket'],
				'Key' => $meta['s3_key']
			));
			if ($is_image){
				foreach($meta['sizes'] as $size => $info){

					$result = $client->deleteObject(array(
						'Bucket' => $info['s3_bucket'],
						'Key'    => $info['s3_key']
					));


				}

			}

		}
	}

	public function  _filter_wp_generate_attachment_metadata($data, $post_id){
		$mime = get_post_mime_type($post_id);
		$parts = explode('/', $mime);
		$is_image = 'image' == array_shift($parts);

		$unique = substr(md5($data['file']), 0, 5);
		$client = $this->get_client();
		$path = dirname($data['file']);
		$filename = basename($data['file']);
		$source_path = WP_CONTENT_DIR . '/uploads/' . $path;

		$opts = $this->get_option_aws();
		$bucket = $opts->bucket;
		$key =  $unique . '/' . $filename;
		$metadata = array(
			'mime' => $mime
		);
		if ($is_image){
			$metadata['width'] = $data['width'];
			$metadata['height'] = $data['height'];
		}
		$result = $client->putObject(array(
			'Bucket'     => $bucket,
			'Key'        => $key,
			'SourceFile' => $source_path . '/' . $filename,
			'ACL'        => 'public-read',
			'Metadata'   => $metadata
		));
		/** @var \Guzzle\Service\Resource\Model $result */
		$data['s3_url'] = $result->get('ObjectURL');
		$data['s3_bucket'] = $bucket;
		$data['s3_key'] = $key;
		if ($is_image){
			foreach($data['sizes'] as $size => $info){

				$key = $unique . '/' . $info['file'];
				$result = $client->putObject(array(
					'Bucket'     => $bucket,
					'Key'        => $key,
					'SourceFile' => $source_path . '/' . $info['file'],
					'ACL'        => 'public-read',
					'Metadata'   => array(
						'height' => $info['height'],
						'width' => $info['width']
					)
				));
				$data[$size]['s3_url'] = $result->get('ObjectURL');
				$data[$size]['s3_bucket'] = $bucket;
				$data[$size]['s3_key'] = $key;

			}
		}
		return $data;
	}

	/**
	 * @return AWSOptions
	 */
	public function get_option_aws(){
		$o = get_option(self::OK_AWS_KEYS);
		if (! $o instanceof AWSOptions){
			$o = new AWSOptions;
		}
		return $o;
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
		$keys = $this->get_option_aws();
		return ! empty($keys->id) && ! empty($keys->secret);
	}

	/**
	 * @return S3Client
	 */
	public function get_client(){
		if (! $this->client instanceof S3Client){
			$keys = $this->get_option_aws();
			$this->client = S3Client::factory(array(
				'key'    => $keys->id,
				'secret' => $keys->secret
			));
		}
		return $this->client;
	}

} 