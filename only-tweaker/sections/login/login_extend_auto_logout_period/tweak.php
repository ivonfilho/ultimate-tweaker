<?php

class OT_login_extend_auto_logout_period_Tweak {
	function settings() {
		return OT_Helper::switcher( 'login_extend_auto_logout_period', array(
			'title' => __( 'Keep logged in for 1 year', OT_SLUG )
		) );
	}

	function tweak() {
		add_filter( 'auth_cookie_expiration', array($this, '_do') );
	}

	function _do($expirein) {
		return 31556926;// 1 year in seconds
	}
}