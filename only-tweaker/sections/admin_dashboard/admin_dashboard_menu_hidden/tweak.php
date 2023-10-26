<?php

class OT_admin_dashboard_menu_hidden_Tweak {
	function settings() {
		return OT_Helper::switcher( 'admin_dashboard_menu_hidden', array(
			'title'    => __( 'Hide dashboard', OT_SLUG ),
		) );
	}

	function tweak() {
        add_action( "current_screen", array( $this, 'checkPage' ));
		add_action( 'admin_menu', array( $this, '_do' ), 110 );
	}

	function checkPage() {
        global $current_screen;

        if($current_screen->id == 'dashboard') {
            wp_redirect('profile.php');
            exit;
        }
    }

	function _do() {
		if ( ! current_user_can( 'viewdashboard' ) ) {
			remove_menu_page( 'index.php' );
			remove_menu_page( 'separator1' );
		}
	}
}