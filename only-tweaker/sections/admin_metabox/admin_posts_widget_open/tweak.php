<?php

class OT_admin_posts_widget_open_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_posts_widget_open', array(
			'title'    => __( 'Open all widgets', OT_SLUG )
		) );
	}

	function tweak() {
		global $pagenow;
		if(in_array($pagenow, array('post-new.php', 'post.php'))) {
			$this->_do();
		}
	}

	function _do() {
		OT_Helper::$_->script('script', __FILE__, array('deps' => array( 'jquery' )));
	}
}