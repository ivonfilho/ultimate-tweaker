<?php

class OT_admin_themes_disable_delete_Tweak {
	function settings() {
		return OT_Helper::switcher( 'admin_themes_disable_delete', array(
			'title' => __( 'Disable deletion', OT_SLUG )
		) );
	}

	function tweak() {
		OT_Helper::blockUserCap('delete_themes');
	}
}