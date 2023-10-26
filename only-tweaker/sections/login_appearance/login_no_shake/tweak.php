<?php

class OT_login_no_shake_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'login_no_shake', array(
			'title'    => __( 'No Shake Login Form', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter('login_head', array($this, '_do'));
	}

	function _do() {
		remove_action('login_head', 'wp_shake_js', 12);
	}
}