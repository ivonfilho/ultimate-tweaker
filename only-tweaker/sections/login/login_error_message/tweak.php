<?php

class OT_login_error_message_Tweak {
	function settings( ) {
		$f = array();
		$f[] = OT_Helper::switcher( 'login_error_message', array(
			'title'       => __( 'Login Error message', OT_SLUG ),
			'off_desc'       => __( 'Default error messages.', OT_SLUG ),
			'on_desc'       => __( 'Always show one custom error message.', OT_SLUG ),
			'desc'       => __( 'We recommend use it, because users can see that login exists or not.', OT_SLUG ),
		) );

		$f[] = OT_Helper::field( '_login_error_message_text', 'text', array(
			'required' => array( 'login_error_message', '=', '1' ),

			'right_title'    => __( 'Custom message:', OT_SLUG ),
			'default'     => __( 'No valid credentials.', OT_SLUG )
		) );

		return $f;
	}

	function tweak() {
		if($this->value && (@$_REQUEST['action'] !== 'register')) {
			add_filter( 'login_errors', array($this, '_do') );
		}
	}

	function _do( $message ) {
		$text = isset($this->options->_login_error_message_text) ? $this->options->_login_error_message_text
			: __( 'No valid credentials.', OT_SLUG );
		return $text;
	}
}