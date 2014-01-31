<?php
namespace ModernMedia\WPLib\MetaTags\Admin;
use ModernMedia\WPLib\Admin\BaseAdminElement;
use ModernMedia\WPLib\MetaTags\MetaTags;
use ModernMedia\WPLib\Scripts;
use ModernMedia\WPLib\Utils;

/**
 * Class MetaTagsMetaBox
 * @package ModernMedia\WPLib\MetaTags\Admin
 *
 * This meta box allows one to edit meta tags
 * for individual posts/pages and
 * custom post types.
 *
 */
class MetaTagsMetaBox extends BaseAdminElement {

	/**
	 * Constructor...
	 */
	public function __construct(){

		$post_types = array_merge(array('post', 'page'), get_post_types());
		//var_dump($post_types);
		$args = array(
			'cap' => 'edit_posts',
			'post_types' => $post_types,
			'title' => 'Meta Tags',
			'type' => self::TYPE_METABOX,
			'metabox_context' => 'advanced',
		);

		parent::__construct($args);
	}

	public function get_post_types(){
		$post_types = array_merge(get_post_types(array('public'=>true, '_builtin'=>false)), array('post', 'page'));
		return $post_types;
	}

	/**
	 * @param int $post_id
	 */
	protected  function html($post_id = null){
		require Utils::get_lib_path('includes/admin/metabox/post_meta_tags.php');
	}

	/**
	 * @param int $post_id
	 */
	protected function on_save($post_id = null){
		MetaTags::inst()->set_post_meta($post_id, $_POST[MetaTags::PMK_META_TAGS]);
	}

	/**
	 * enqueue the counter and the image uploader js
	 */
	protected function on_admin_enqueue_scripts(){
		wp_enqueue_media();
		$s = Scripts::inst();
		$s->enqueue(Scripts::UPLOADER);
		$s->enqueue(Scripts::CHAR_COUNT);
	}

} 