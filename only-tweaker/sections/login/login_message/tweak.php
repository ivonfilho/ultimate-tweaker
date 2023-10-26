<?php

class OT_login_message_Tweak {
	function settings( ) {
		return OT_Helper::field( 'login_message', 'textarea', array(
			'title'       => __( 'Login Form Message', OT_SLUG ),
			'desc'       => __( 'Set custom information message, will be shown under form.', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter( 'login_message', array($this, '_do') );
	}

	function _do( $message ) {
		if ( empty($message) ){
			return "<p class='message' style='margin-bottom: 5px;border-left-color: transparent;'>".$this->value."</p>";
		} else {
			return $message;
		}
	}
}