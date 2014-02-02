<?php
namespace ModernMedia\WPLib\Carousel\Admin;
use ModernMedia\WPLib\Admin\BaseAdminElement;
use ModernMedia\WPLib\Carousel\Carousel;
use ModernMedia\WPLib\Utils;

class CarouselItemsMetaBox extends BaseAdminElement {

	public function __construct(){
		$init = array(
			'type'=>self::TYPE_METABOX,
			'id' => 'mm-wp-lib-carousel-items',
			'post_types' => array(Carousel::PT_CAROUSEL),
			'title' => __('Carousel Items'),
		);
		parent::__construct($init);
	}

	public function html($post_id = null){
		require Utils::get_lib_path('includes/admin/metabox/carousel_items.php');
	}

} 