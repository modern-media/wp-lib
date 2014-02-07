<?php
namespace ModernMedia\WPLib\Admin\Panel;
use ModernMedia\WPLib\Admin\BaseAdminElement;
use ModernMedia\WPLib\Utils;

class DebuggerPanel extends BaseAdminElement {

	public function __construct(){
		parent::__construct(array(
			'id' => 'mm-wp-lib-dbg',
			'title' => 'Debugger Data',
			'type' => self::TYPE_PANEL,
		));
	}

	public function html($post_id = null){
		require Utils::get_lib_path('includes/admin/panel/debugger.php');
	}
} 