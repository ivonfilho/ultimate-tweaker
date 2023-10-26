<?php

class OT_admin_posts_collapse_no_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_posts_collapse_no', array(
			'title'    => __( 'Disable widgets collapsing', OT_SLUG )
		) );
	}

	function tweak() {
		global $pagenow;
		if(in_array($pagenow, array('post-new.php', 'post.php'))) {
			$this->_do();
		}
	}

	function _do() {
		OT_Helper::$_->script('script', __FILE__, array( 'in_footer' =>true, 'deps' => array( 'jquery' ,'postbox' )));
	}
}