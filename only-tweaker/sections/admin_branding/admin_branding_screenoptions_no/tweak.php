<?php

class OT_admin_branding_screenoptions_no_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_branding_screenoptions_no', array(
			'title'   => __( 'Remove "Screen Options" panel', OT_SLUG ),
			'desc'   => __( 'Contextual options panels will be deleted in all pages.', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter('screen_options_show_screen', '__return_false');
	}
}