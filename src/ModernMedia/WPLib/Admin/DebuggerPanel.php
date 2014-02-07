<?php
namespace ModernMedia\WPLib\Admin;

use ModernMedia\WPLib\Debugger;

class DebuggerPanel extends BaseAdminElement {

	public function __construct(){
		parent::__construct(array(
			'id' => 'mm-wp-lib-dbg',
			'title' => 'Debugger Data',
			'type' => self::TYPE_PANEL,
		));
	}

	public function html($post_id = null){
		echo Debugger::inst()->get_formatted_data();
	}
} 