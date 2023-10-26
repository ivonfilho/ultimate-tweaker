<?php

class OT_admin_dashboard_collapse_no_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_dashboard_collapse_no', array(
			'title'    => __( 'Disable widgets collapsing', OT_SLUG )
		) );
	}

	function tweak() {
		add_action('load-index.php', array($this, '_do'));
	}

	function _do() {
		OT_Helper::$_->script('script', __FILE__, array( 'in_footer' =>true, 'deps' => array( 'jquery' ,'postbox' )));
	}
}