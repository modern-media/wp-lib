<?php
namespace ModernMedia\WPLib\Admin;
use ModernMedia\WPLib\Constants;
use ModernMedia\WPLib\Data\AjaxResponse;
use ModernMedia\WPLib\Utils;

/**
 * Class BaseAdminForm
 * @package ModernMedia\EventPublisher\Admin
 *
 * The abstract base class for meta boxes, panels, and custom post editors
 */
abstract class BaseAdminElement {


	const TYPE_METABOX = 'metabox';
	const TYPE_EDITOR = 'editor';
	const TYPE_PANEL = 'panel';

	private $init = array();


	/********************************
	 * Override-able Functions      *
	 *******************************/

	/**
	 * Allows child classes to hook into 'plugins_loaded'
	 */
	protected function on_plugins_loaded(){}

	/**
	 * Allows child classes to hook into 'admin_enqueue_scripts'
	 */
	protected function on_admin_enqueue_scripts(){}

	/**
	 * @param string $action
	 * @param AjaxResponse $resp
	 */
	protected function on_ajax($action, &$resp){}

	/**
	 * @param int|null $post_id
	 */
	protected function on_save($post_id = null){}

	/**
	 * @param int|null $post_id
	 */
	protected function html($post_id = null){}



	/**
	 * COMMON VARIABLES
	 */

	/**
	 * @var null|array
	 */
	public $form_data = null;

	/**
	 * @var array
	 */
	public $errors = array();

	/**
	 * @var string
	 */
	public $message = '';

	/**
	 * Constructor taking an initialization array.
	 *
	 * The $init array can contain the following keys:
	 *
	 * - type string -- required, must be one of the type constants above
	 * - id string -- the unique id of this admin element
	 * - cap string -- the user capability required by this element
	 * - ajax_actions array|string  -- a single string or array of strings that this element will respond to
	 * - post_types	array|string -- the post types that a metabox or editor is applicable to
	 * - title string -- for boxes and panels, the title
	 *
	 * - panel_screen_icon string -- for panels, the icon id used by screen_icon()
	 * - panel_is_top bool - for panels, whether it's a top-level menu item
	 * - panel_position int - for top-level panels, the position
	 * - panel_parent string -- for child panels, the parent slug
	 *
	 * - metabox_context string -- 'normal', 'advanced', or 'side'
	 * - metabox_priority string -- 'high', 'core', 'default' or 'low'
	 *
	 * @param array $init
	 * @throws \Exception
	 */
	public function __construct($init){

		$defaults = array(
			'id' => strtolower(str_replace('\\', '_', get_class($this))),
			'cap' => Constants::USER_ROLE_ADMINISTRATOR,
			'ajax_actions' => array(),
			'post_types' => array(),
			'title' => 'Untitled',
			'panel_screen_icon' => strtolower(str_replace('\\', '_', get_class($this))),
			'panel_is_top' => false,
			'panel_position' => 200,
			'panel_parent' => 'options-general.php',
			'metabox_context' => 'side',
			'metabox_priority' => 'default',

		);
		$this->init = array_merge($defaults, $init);


		/**
		 * Add the save hooks...
		 *
		 * In the case of admin panels,
		 * this should be the 'init' hook,
		 * allowing the panel to redirect.
		 *
		 * For custom editors and meta boxes,
		 * we use the 'save_post' hook.
		 */
		switch($this->get_type()){
			case self::TYPE_METABOX:
			case self::TYPE_EDITOR:
				$hook = 'save_post';
				break;
			case self::TYPE_PANEL:
				$hook = 'init';
				break;
			default:
				$hook = false;
				break;
		}

		if ($hook){
			add_action($hook, function($post_id = null)  {
				if (! $this->get_is_current()) return;
				if (! Utils::is_submitting()) return;
				if (! current_user_can($this->get_cap())) return;
				if (! $this->check_nonce()) return;
				$this->on_save($post_id);
			});
		}

		/**
		 * Add the hooks to output the HTML.
		 *
		 * The hooks and the HTML methods
		 * differ according to what type of
		 * element $this is.
		 *
		 */
		switch($this->get_type()){
			case self::TYPE_METABOX:
				add_action('add_meta_boxes', function(){
					if (! current_user_can($this->get_cap())) return;
					if (! $this->get_is_current()) return;
					foreach($this->get_post_types() as $pt){
						add_meta_box(
							$this->get_id(),
							$this->get_title(),
							function($post){
								echo PHP_EOL;
								$this->echo_nonce();
								echo PHP_EOL;
								$this->html($post->ID);
								echo PHP_EOL;
							},
							$pt,
							$this->get_metabox_context(),
							$this->get_metabox_priority()
						);
					}
				});
				break;
			case self::TYPE_EDITOR:
				add_action('edit_form_after_title', function(){
					if (! current_user_can($this->get_cap())) return;
					if (! $this->get_is_current()) return;
					global $post;
					echo PHP_EOL;
					$this->echo_nonce();
					echo PHP_EOL;
					$this->html($post->ID);
					echo PHP_EOL;
				});
				break;
			case self::TYPE_PANEL:
				add_action('admin_menu', function(){
					if (! current_user_can($this->get_cap())) return;
					$func = function(){
						echo '<div class="wrap">' . PHP_EOL;
						printf(
							'<h2>%s</h2>',
							$this->get_title()
						);

						if (! empty($this->message)){
							printf(
								'<div class="updated alert alert-success"><p>%s</p></div>',
								$this->message
							);
						}

						$this->html();
						echo PHP_EOL . '</div>' . PHP_EOL;
					};
					if ($this->get_panel_is_top()){
						add_menu_page(
							$this->get_title(),
							$this->get_title(),
							$this->get_cap(),
							$this->get_id(),
							$func,
							'none',
							$this->get_panel_position()
						);

					} else {
						add_submenu_page(
							$this->get_panel_parent(),
							$this->get_title(),
							$this->get_title(),
							$this->get_cap(),
							$this->get_id(),
							$func
						);
					}
				});
				break;
		}


		/**
		 * Let child classes hook into plugins_loaded
		 */
		add_action('plugins_loaded', function(){
			if (! current_user_can($this->get_cap())) return;
			$this->on_plugins_loaded();
		});

		/**
		 * Let child classes hook into admin_enqueue_scripts
		 */
		add_action('admin_enqueue_scripts', function(){
			if (! $this->get_is_current()) return;
			if (! current_user_can($this->get_cap())) return;
			$this->on_admin_enqueue_scripts();
		});


		/**
		 * Hook into the ajax action
		 */
		foreach($this->get_ajax_actions() as $action){
			$slug = 'wp_ajax_' . $this->ajax_action_from_action($action);

			add_action($slug, function(){
				$action = trim(stripslashes($_POST['action']));
				$action = $this->action_from_ajax_action($action);
				$ajax_actions = $this->get_ajax_actions();
				if (! in_array($action, $ajax_actions)) return;
				$resp = new AjaxResponse;
				$resp->nonce = $this->get_ajax_nonce_value($action);
				if (! current_user_can($this->get_cap())) $resp->respond_with_error('security','not authorized (cap)');
				if (! $this->check_ajax_nonce($action)) $resp->respond_with_error('security', 'not authorized (nonce)');
				$this->on_ajax($action, $resp);
				die();
			});
		}


		/**
		 * Check if there's a flash message and stick it into
		 * $this->message
		 */
		$ck = $this->get_flash_cookie_key($this->get_id());
		if (isset($_COOKIE[$ck])){
			$message = trim($_COOKIE[$ck]);
			if (! empty($message)){
				$this->message = $message;
			}
			setcookie($ck, null, time() - (30 * 24 * 60 * 60), '/');
		}
	}



	/********************************
	 * Accessors to the private $init property             *
	 *******************************/
	/**
	 * They type property is required
	 * @throws \Exception
	 * @return string
	 */
	public final function get_type(){
		//make sure we have a valid type...
		if (empty($this->init['type']) || ! in_array($this->init['type'], array(self::TYPE_METABOX, self::TYPE_PANEL, self::TYPE_EDITOR))){
			throw new \Exception('Invalid admin element type.');
		}
		return $this->init['type'];
	}

	/**
	 * @return string
	 */
	public final function get_id(){
		return $this->init['id'];
	}

	/**
	 * @return string
	 */
	public final function get_cap(){
		return $this->init['cap'];
	}
	/**
	 * @return array
	 */
	public final function get_ajax_actions(){
		return $this->init['ajax_actions'];

	}

	/**
	 * @return array
	 */
	public final function get_post_types(){
		return $this->init['post_types'];
	}

	/**
	 * @return string
	 */
	public final function get_title(){
		return $this->init['title'];
	}

	/**
	 * @return string
	 */
	public final function get_panel_screen_icon(){
		return $this->init['panel_screen_icon'];
	}

	/**
	 * @return bool
	 */
	public final function get_panel_is_top(){
		return $this->init['panel_is_top'];
	}

	/**
	 * @return int
	 */
	public final function get_panel_position(){
		return $this->init['panel_position'];
	}

	/**
	 * @return string
	 */
	public final function get_panel_parent(){
		return $this->init['panel_parent'];
	}

	/**
	 * @return string
	 */
	public final function get_metabox_priority(){
		return $this->init['metabox_priority'];
	}

	/**
	 * @return string
	 */
	public final function get_metabox_context(){
		return $this->init['metabox_context'];
	}
	/********************************
	 * END Accessors to the private $init property             *
	 *******************************/

	/**
	 * "Current means slightly different things, dependent on
	 * the type of $this element.  For meta boxes and custom
	 * admin panels, it means that an applicable
	 * post type is being edited or updated. For admin panels
	 * it means that the panel is active.
	 *
	 * @return bool
	 */
	public final function get_is_current(){
		if (! is_admin()) return false;
		switch ($this->get_type()){
			case self::TYPE_PANEL:
				if (! isset($_GET['page'])) return false;
				if ($_GET['page'] != $this->get_id()) return false;
				return true;
				break;
			case self::TYPE_EDITOR:
			case self::TYPE_METABOX:
				if (! is_admin()) return false;
				global $pagenow;
				if (! in_array($pagenow, array('post.php', 'post-new.php'))) return false;
				$type = '';
				if ('post-new.php' == $pagenow){
					$type = $_GET['post_type'];
				} else {
					if (Utils::is_submitting()){
						if (isset ($_POST['post_type'])){
							$type = trim(stripslashes($_POST['post_type']));
						}
					} else {
						if (isset ($_GET['post'])){
							$type = get_post_type($_GET['post']);
						}
					}
				}
				if (! in_array($type, (array) $this->get_post_types())) return false;
				return true;
				break;
		}
		return false;
	}

	/***
	 * UTILITIES
	 */


	/**
	 * @param string $action
	 * @return string
	 */
	public final function ajax_action_from_action($action){
		return $this->get_id() . $action;
	}

	/**
	 * @param $ajax_action
	 * @return string
	 */
	public final function action_from_ajax_action($ajax_action){
		return str_replace($this->get_id(), '', $ajax_action);
	}

	/**
	 * @return string
	 */
	public final function get_nonce_key(){
		return $this->get_id() . '_nonce';
	}

	/**
	 * Print out the nonce fields
	 */
	public final function echo_nonce(){
		wp_nonce_field($this->get_id(), $this->get_nonce_key());
	}

	/**
	 * @return bool
	 */
	public final function check_nonce(){
		return wp_verify_nonce($_POST[$this->get_nonce_key()], $this->get_id());
	}


	/**
	 * @param $action
	 * @return string
	 */
	public final function get_ajax_nonce_value($action){
		return wp_create_nonce($this->ajax_action_from_action($action));
	}

	/**
	 * @param string $action
	 * @return bool
	 */
	public final function check_ajax_nonce($action){
		if (! isset($_POST['nonce'])) return false;
		return wp_verify_nonce($_POST['nonce'], $this->ajax_action_from_action($action));
	}



	/**
	 * @param $form_id
	 * @return string
	 */
	public final function get_flash_cookie_key($form_id){
		return Constants::CK_KEY_ADMIN_FLASH_MESSAGES . $form_id;
	}

	/**
	 * @param $form_id
	 * @param $message
	 */
	public final function set_flash_message($form_id, $message){
		$ck = $this->get_flash_cookie_key($form_id);
		setcookie($ck, $message, null, '/');
	}

	/**
	 * @param $id
	 * @return string
	 */
	public static final function get_panel_url($id){
		$slug = 'admin.php';
		$url = admin_url($slug);
		$url = add_query_arg('page', $id, $url);
		return $url;
	}

	/**
	 * Display $this->errors
	 */
	public function display_errors(){

		echo PHP_EOL;
		printf(
			'<div class="alert alert-warning error-display"%s><h4>%s:</h4><ul>',
			0 == count($this->errors) ? ' style="display:none"' : '',
			count($this->errors) == 1 ? __('Please correct the following error'):  __('Please correct the following errors')
		);
		$this->display_errors_recursive($this->errors);
		echo '</ul></div>';
		echo PHP_EOL;


	}

	/**
	 * @param $errors
	 */
	public function display_errors_recursive($errors){
		foreach($errors as $id=>$error){
			if (is_array($error)){
				$this->display_errors_recursive($error);
			} else {
				printf(
					'<li class="error-li" id="error-%s">%s</li>',
					esc_attr($id),
					$error
				);
			}


		}
	}

} 