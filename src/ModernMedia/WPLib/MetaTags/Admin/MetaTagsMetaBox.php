<?php
namespace ModernMedia\WPLib\MetaTags\Admin;
use ModernMedia\WPLib\Admin\BaseAdminElement;
use ModernMedia\WPLib\MetaTags\MetaTags;
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
		$args = array(
			'cap' => 'edit_posts',
			'post_types' => $post_types,
			'title' => 'Meta Tags',
			'type' => self::TYPE_METABOX,
			'metabox_context' => 'advanced',
		);

		parent::__construct($args);
	}

	/**
	 * @param int $post_id
	 */
	public function html($post_id = null){
		require Utils::get_lib_path('includes/admin/metabox/meta_tags.php');
	}

	/**
	 * @param int $post_id
	 */
	protected function on_save($post_id = null){
		MetaTags::inst()->set_post_meta($post_id, $_POST);
	}
} 