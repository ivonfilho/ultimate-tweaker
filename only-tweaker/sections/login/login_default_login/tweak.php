<?php

class OT_login_default_login_Tweak {
	function settings( ) {
		return OT_Helper::field( 'login_default_login', 'text', array(
			'title'    => __( 'Login', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter('login_footer', array($this, '_do'));
	}

	function _do() {
		echo "<script>document.getElementById('user_login').value = \"".addslashes($this->value)."\";</script>";
	}
}