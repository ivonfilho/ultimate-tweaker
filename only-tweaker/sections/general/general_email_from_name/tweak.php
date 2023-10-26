<?php

class OT_general_email_from_name_Tweak {
	function settings( ) {
		$f = array();

		$f[] = OT_Helper::field( 'general_email_from_name', 'text', array(
			'title'    => __( 'Change from name', OT_SLUG ),
			'desc'    => __( 'You can define any name, name will be used for all sent emails.<br/> Default name is "&#87;ordPress"', OT_SLUG ),
		) );

		$f[] = OT_Helper::field( 'info', array(
			'desc'    => __( 'This address and name will be used for all sended emails.', OT_SLUG )
		) );

		return $f;
	}

	function tweak() {
		add_filter('wp_mail_from_name', array($this, 'wp_mail_from_name'));
	}

	function wp_mail_from_name($old) {
		return $this->value;
	}
}