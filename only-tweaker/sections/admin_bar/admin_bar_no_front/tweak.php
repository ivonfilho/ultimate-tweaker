<?php

class OT_admin_bar_no_front_Tweak {
	function settings() {
		return OT_Helper::switcher( 'admin_bar_no_front', array(
			'title'    => __( 'Hide on site', OT_SLUG ),
			'on_desc'  => __( 'Toolbar is hidden on site for everyone', OT_SLUG ),
			'off_desc' => __( 'Toolbar is visible on site for everyone', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter( 'show_admin_bar', '__return_false' );
	}
}