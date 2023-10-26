<?php

class OT_admin_dashboard_dragging_no_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_dashboard_dragging_no', array(
			'title'    => __( 'Disable widgets dragging', OT_SLUG )
		) );
	}

	function tweak() {
//		global $pagenow;
//		var_dump($pagenow);
		add_action('load-index.php', array($this, '_do'));
	}

	function _do() {
		OT_Helper::$_->script('script', __FILE__, array('deps' => array( 'jquery' )));
		add_action('admin_head', array($this, '_css'));
	}

	function _css() {
		echo '<style type="text/css">h3.hndle { cursor: default !important; } .metabox-holder .postbox-container .empty-container {border:none !important;}</style>';
	}
}