<?php
namespace ModernMedia\WPLib\Admin\MetaBox;
use ModernMedia\WPLib\Admin\BaseAdminElement;
use ModernMedia\WPLib\Carousel;
use ModernMedia\WPLib\Data\CarouselItemData;
use ModernMedia\WPLib\Scripts;
use ModernMedia\WPLib\Utils;

class CarouselItemsMetaBox extends BaseAdminElement {

	public function __construct(){
		$init = array(
			'type'=>self::TYPE_METABOX,
			'id' => 'mm-wp-lib-carousel-items',
			'post_types' => array(Carousel::PT_CAROUSEL),
			'title' => __('Carousel Items'),
			'metabox_context' => 'advanced',
			'metabox_priority' => 'default',
		);
		parent::__construct($init);
	}

	public function html($post_id = null){
		require Utils::get_lib_path('includes/admin/metabox/carousel/carousel_items.php');
	}

	public function on_admin_enqueue_scripts(){
		Scripts::inst()->enqueue(Scripts::UPLOADER);
		wp_enqueue_media();
	}

	public function on_save($post_id = null){
		$items = isset($_POST[Carousel::PMK_ITEMS]) ? $_POST[Carousel::PMK_ITEMS] : array();
		if (! is_array($items)){
			$items = array();
		}
		$clean = array();
		foreach($items as $item){
			$o = new CarouselItemData($item);
			$clean[] = $o;
		}
		update_post_meta($post_id, Carousel::PMK_ITEMS, $clean);
	}

} 