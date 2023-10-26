<?php

class OT_admin_bar_subscriber_no_Tweak {
	function settings( ) {
		//DISABLED
		return OT_Helper::switcher( 'admin_bar_subscriber_no', array(
			'title'    => __( 'Hide on site for subscriber', OT_SLUG ),
			'on_desc'    => __( 'Toolbar is hidden for subscriber.', OT_SLUG ),
			'off_desc'    => __( 'Toolbar is visible for subscriber.', OT_SLUG ),
		) );
	}

	function tweak() {
		return;
		add_action( 'init', array( &$this, '_init' ) );
	}


	function _init() {
		if ( current_user_can('subscriber') ) {
			add_filter( 'show_admin_bar', '__return_false' );
		}
	}
}