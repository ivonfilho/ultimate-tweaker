<?php

class OT_general_email_from_Tweak {
	function settings( ) {
		$f = array();

		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}

		$from_email = 'wordpress@' . $sitename;

		$f[] = OT_Helper::field( 'general_email_from', 'text', array(
			'title'    => __( 'Change from address', OT_SLUG ),
			'desc'    => __( 'You can define any email address, address will be used for all sent emails.<br/> Default email is ' . $from_email, OT_SLUG )
		) );

		return $f;
	}

	function tweak() {
		add_filter('wp_mail_from', array($this, 'wp_mail_from'));
	}

	function wp_mail_from($old) {
		return $this->value;
	}
}