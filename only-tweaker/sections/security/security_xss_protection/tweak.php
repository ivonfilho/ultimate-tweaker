<?php

class OT_security_xss_protection_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'security_xss_protection', array(
			'title'   => __( 'Add xss protection header', OT_SLUG )
		) );
	}

	function tweak() {
		header('X-XSS-Protection: 1; mode=block');
	}
}