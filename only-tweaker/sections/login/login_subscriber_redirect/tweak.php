<?php

class OT_login_subscriber_redirect_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'login_subscriber_redirect', array(
			'title'    => __( 'Redirect subscribers to home page', OT_SLUG ),
			'on_desc'    => __( 'Subscriber will be redirected to homepage after login', OT_SLUG ),
			'off_desc'    => __( 'Subscriber will be redirected to admin page after login', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action('login_redirect', array($this, '_do'), 10, 3);
	}

	function _do($redirect_to, $user) {
		global $user;

		if ( isset($user->ID) && user_can($user, 'subscriber') ) {
			$redirect_to = get_home_url();
			return $redirect_to;
		}

		return $redirect_to;
	}
}