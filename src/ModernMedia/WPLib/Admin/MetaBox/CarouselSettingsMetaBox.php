<?php
namespace ModernMedia\WPLib\Admin\MetaBox;
use ModernMedia\WPLib\Carousel;
use ModernMedia\WPLib\Admin\BaseAdminElement;
use ModernMedia\WPLib\Utils;
use ModernMedia\WPLib\Data\CarouselSettingsData;
class CarouselSettingsMetaBox extends BaseAdminElement {

	public function __construct(){
		$init = array(
			'type'=>self::TYPE_METABOX,
			'id' => 'mm-wp-lib-carousel-settings',
			'post_types' => array(Carousel::PT_CAROUSEL),
			'title' => __('Carousel Settings'),
		);
		parent::__construct($init);
	}

	public function html($post_id = null) {

		require Utils::get_lib_path('includes/admin/metabox/carousel/carousel_settings.php');

	}


	public function on_save($post_id = null) {
		$o  = new CarouselSettingsData($_POST[Carousel::PMK_SETTINGS]);
		update_post_meta($post_id, Carousel::PMK_SETTINGS, $o);
	}

}