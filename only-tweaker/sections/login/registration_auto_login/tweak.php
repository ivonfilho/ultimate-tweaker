<?php

class OT_registration_auto_login_Tweak {
	function settings() {
		return OT_Helper::switcher( 'registration_auto_login', array(
			'title'   => __( 'Auto-Login after registration', OT_SLUG ),
			'on_desc' => __( 'User will be logged in after registration', OT_SLUG ),
			'off_desc' => __( 'User must log in manually after registration', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action( 'user_register', array( $this, '_do' ) );
	}

	function _do( $user_id ) {
		if ( $user_id ) {
			wp_set_current_user( $user_id );
			wp_set_auth_cookie( $user_id );
		}
	}
}