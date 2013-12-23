<?php
namespace ModernMedia\WPLib\Data;

class AjaxResponse {

	/**
	 * @var string
	 */
	public $nonce = '';

	/**
	 * @var bool
	 */
	public $is_error = false;

	/**
	 * @var array
	 */
	public $errors = array();

	/**
	 * @var string
	 */
	public $error_html = '';

	/**
	 * @var mixed
	 */
	public $data = null;


	/**
	 * @param $key
	 * @param $error
	 */
	public function add_error($key, $error){
		$this->errors[$key] = $error;
		$this->is_error = true;
	}

	/**
	 * @param $data
	 */
	public function set_data($data){
		$this->data = $data;
	}

	/**
	 * @param $data
	 */
	public function respond_with_data($data){
		$this->set_data($data);
		$this->respond();
	}


	/**
	 * @param $key
	 * @param $error
	 */
	public function respond_with_error($key, $error){
		$this->add_error($key, $error);
		$this->respond();
	}

	/**
	 *
	 */
	public function respond(){
		if (count($this->errors)){
			$lis = '';
			foreach($this->errors as $key => $err){
				$lis .= sprintf(
					'<li class="error-li error-%s">%s</li>',
					esc_attr($key),
					$err
				);
			}
			$this->error_html = sprintf(
				'<p><strong>%s</strong></p><ul>%s</ul>',
				count($this->errors) == 1 ? __('Please correct the following error:') : __('Please correct the following errors:'),
				$lis
			);

		}
		die(json_encode($this));
	}
} 