<?php

class OT_admin_dashboard_widget_open_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_dashboard_widget_open', array(
			'title'    => __( 'Open all widgets', OT_SLUG )
		) );
	}

	function tweak() {
//		global $pagenow;
//		var_dump($pagenow);
		add_action('load-index.php', array($this, '_do'));
	}

	function _do() {
		OT_Helper::$_->script('script', __FILE__, array('deps' => array( 'jquery' )));
	}
}