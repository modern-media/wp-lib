<?php
namespace ModernMedia\WPLib\Theme;

class CommentWalker extends \Walker_Comment {
	public function start_lvl(&$output, $depth = 0, $args = array()){
		if ($depth == 0){
			//$output .= '<ul class="media-list">';
		}

	}
	public function end_lvl(&$output, $depth = 0, $args = array()){

		if ($depth == 0){
			//$output .= '</ul>';
		}
	}
	public function start_el(&$output, $comment, $depth = 0, $args = array(), $id = 0){
		ob_start();
		require MODERNMEDIA_ELCDML_THEME_ROOT . '/templates/comments/el_start.php';
		$output .= ob_get_clean();
	}
	public function end_el(&$output, $comment, $depth = 0, $args = array()){
		ob_start();
		require MODERNMEDIA_ELCDML_THEME_ROOT . '/templates/comments/el_end.php';
		$output .= ob_get_clean();
	}

	protected function comment( $comment, $depth, $args ) {
		echo $comment->comment_ID; die('hey');
	}
} 