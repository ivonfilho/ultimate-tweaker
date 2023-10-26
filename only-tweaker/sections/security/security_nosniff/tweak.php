<?php

class OT_security_nosniff_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'security_nosniff', array(
			'title'   => __( 'Add nosniff header', OT_SLUG )
		) );
	}

	function tweak() {
		header('X-Content-Type-Options: nosniff');
	}
}