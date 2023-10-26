<?php

class OT_login_redirect_home_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'login_redirect_home', array(
			'title'       => __( 'Redirect to site home page', OT_SLUG ),
		) );
	}

	function tweak() {
        add_action('login_redirect', array($this, '_do'), 10, 3);
	}

	function _do() {
        $redirect_to = get_home_url();
        return $redirect_to;
	}
}