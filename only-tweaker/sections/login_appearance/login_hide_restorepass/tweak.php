<?php

class OT_login_hide_restorepass_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'login_hide_restorepass', array(
			'title'    => __( 'Hide "Lost your password?" link', OT_SLUG ),
			'desc'    => __( 'Also capability will be disabled.', OT_SLUG ),
		) );
	}

	function tweak() {
	    add_filter( 'login_form_lostpassword', array($this, 'redirect'));
        add_filter( 'allow_password_reset', array( $this, 'disable' ) );
        add_filter( 'gettext',              array( $this, 'removeText' ) );
	}

	function removeText($text) {
        return str_replace( array('Lost your password?', 'Lost your password'), '', trim($text, '?') );
	}

    function redirect() {
        wp_redirect(wp_login_url());
        exit();
    }

    function disable()
    {
        return false;
    }
}