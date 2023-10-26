<?php

class OT_admin_no_notice_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_no_notice', array(
			'title'   => __( 'Hide all notices', OT_SLUG ),
			'desc'   => __( 'Totally hides all notices', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action('in_admin_header', array($this, '_do'), 0);
	}

	function _do() {
		$hasUpdate = has_action('admin_notices', 'update_nag');
		remove_all_actions('admin_notices');
		if($hasUpdate) {
			add_action( 'admin_notices', 'update_nag', 3 );
		}
	}
}