<?php

class OT_admin_branding_help_no_Tweak {
	function settings() {
		return OT_Helper::switcher( 'admin_branding_help_no', array(
			'title'   => __( 'Remove "Help" panel', OT_SLUG ),
			'desc'   => __( 'Contextual help panels will be deleted in all pages.', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter( 'contextual_help', array($this, 'contextual_help'), 999, 3 );
	}
	function contextual_help($old_help, $screen_id, $screen){
		$screen->remove_help_tabs();
		return $old_help;
	}
}