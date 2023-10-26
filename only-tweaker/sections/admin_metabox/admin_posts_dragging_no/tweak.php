<?php

class OT_admin_posts_dragging_no_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_posts_dragging_no', array(
			'title'    => __( 'Disable widgets dragging', OT_SLUG )
		) );
	}

	function tweak() {
		global $pagenow;
		if(in_array($pagenow, array('post-new.php', 'post.php'))) {
			$this->_do();
		}
//		var_dump($pagenow);
	}

	function _do() {
		OT_Helper::$_->script('script', __FILE__, array('deps' => array( 'jquery' )));
		add_action('admin_head', array($this, '_css'));
	}

	function _css() {
		echo '<style type="text/css">h3.hndle { cursor: default !important; }</style>';
	}
}