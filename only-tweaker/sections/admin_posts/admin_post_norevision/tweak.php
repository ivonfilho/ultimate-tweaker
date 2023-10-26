<?php

class OT_admin_post_norevision_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_post_norevision', array(
			'title'   => __( 'Disable revisions', OT_SLUG )
		) );
	}

	function tweak() {
		remove_action ( 'post_updated', 'wp_save_post_revision' );
	}
}