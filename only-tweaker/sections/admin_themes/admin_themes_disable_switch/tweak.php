<?php

class OT_admin_themes_disable_switch_Tweak {
	function settings() {
		return OT_Helper::switcher( 'admin_themes_disable_switch', array(
			'title' => __( 'Disable switching', OT_SLUG )
		) );
	}

	function tweak() {
		OT_Helper::blockUserCap('switch_themes');
	}
}