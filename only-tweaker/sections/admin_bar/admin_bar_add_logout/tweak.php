<?php

class OT_admin_bar_add_logout_Tweak {
	function settings() {
		return OT_Helper::switcher( 'admin_bar_add_logout', array(
			'title' => __( 'Add Log Out Button', OT_SLUG )
		) );
	}

	function tweak() {
		add_action( 'admin_bar_menu', array( $this, '_do' ), 1 );
	}

	function _do() {
		if(!is_user_logged_in()) return;

		global $wp_admin_bar;
		$wp_admin_bar->add_menu( array(
			'id' => 'ut-log-out',
			'parent'    => 'top-secondary',
			'title' => __( 'Log Out' ),
			'href'  => wp_logout_url(home_url())
		) );
	}
}