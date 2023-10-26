<?php

class OT_admin_users_remove_color_picker_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_users_remove_color_picker', array(
			'title'   => __( 'Hide "Admin Color Scheme" selection', OT_SLUG ),
			'on_desc'   => __( 'User can\'t change color scheme in "Your Profile" page.', OT_SLUG ),
			'off_desc'   => __( 'User can change color scheme in "Your Profile" page.', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action( 'admin_head' , array($this, '_do'));
	}

	function _do() {
		global $_wp_admin_css_colors;
		$_wp_admin_css_colors = 0;
	}
}