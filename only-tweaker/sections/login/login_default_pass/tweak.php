<?php

class OT_login_default_pass_Tweak {
	function settings( ) {
		return OT_Helper::field( 'login_default_pass', 'text', array(
			'title'    => __( 'Password', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter('login_footer', array($this, '_do'));
	}

	function _do() {
		echo "<script>document.getElementById('user_pass').value = \"".addslashes($this->value)."\";</script>";
	}
}