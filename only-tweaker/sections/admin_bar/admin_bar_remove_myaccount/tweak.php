<?php

class OT_admin_bar_remove_myaccount_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_bar_remove_myaccount', array(
			'title'   => __( 'My Account', OT_SLUG )
		) );
	}

	function tweak() {
		add_action( 'wp_before_admin_bar_render', array($this, '_do') );
	}

	function _do() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('my-account');
	}
}