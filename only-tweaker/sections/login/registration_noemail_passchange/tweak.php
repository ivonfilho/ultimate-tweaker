<?php

class OT_registration_noemail_passchange_Tweak {
	function settings() {
		return OT_Helper::switcher( 'registration_noemail_passchange', array(
			'title' => __( 'No password change notification', OT_SLUG )
		) );

	}

	function tweak() {
		if ( !function_exists( 'wp_password_change_notification' ) ) {
			function wp_password_change_notification() {}
		}
	}
}