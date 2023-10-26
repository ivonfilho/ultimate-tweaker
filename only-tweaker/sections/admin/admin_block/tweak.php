<?php

class OT_admin_block_Tweak {
	function isVisible() {
		return !(in_array(OT_Helper::getRequestRole(), array('administrator', '')));
	}

	function settings( ) {
		return OT_Helper::switcher( 'admin_block', array(
			'title'    => __( 'Block admin page', OT_SLUG ),
			'on_desc'    => __( 'User will be redirected to homepage', OT_SLUG ),
			'off_desc'    => __( 'User can see admin page', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter( 'show_admin_bar', '__return_false' );
		add_action( 'init', array( &$this, '_init' ), 0);
	}

	function _init() {
		if ( is_user_logged_in() && is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			wp_redirect(home_url());
			exit;
		}
	}
}