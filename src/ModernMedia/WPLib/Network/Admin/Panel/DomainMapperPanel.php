<?php
namespace ModernMedia\WPLib\Network\Admin\Panel;
use ModernMedia\WPLib\Admin\BaseAdminElement;
use ModernMedia\WPLib\Constants;

class DomainMapperPanel extends BaseAdminElement {

	public function __construct(){
		$args = array(
			'type' => self::TYPE_PANEL,
			'title' => 'Domain Mapping',
		);
		parent::__construct($args);
	}

	/**
	 * @param int|null $post_id
	 */
	protected function on_save($post_id = null){

	}

	/**
	 * @param int|null $post_id
	 */
	protected function html($post_id = null){

	}
} 