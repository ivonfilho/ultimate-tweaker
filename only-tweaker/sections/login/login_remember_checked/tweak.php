<?php

class OT_login_remember_checked_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'login_remember_checked', array(
			'title'    => __( 'Check "Remember Me"', OT_SLUG ),
			'on_desc'    => __( 'Checkbox will be checked by default.', OT_SLUG ),
			'off_desc'    => __( 'Checkbox will be unchecked by default.', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter('login_footer', array($this, '_do'));
	}

	function _do() {
		echo "<script>document.getElementById('rememberme').checked = true;</script>";
	}
}