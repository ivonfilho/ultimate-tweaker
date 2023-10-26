<?php

class OT_admin_themes_disable_install_Tweak {
	function settings() {
		return OT_Helper::switcher( 'admin_themes_disable_install', array(
			'title' => __( 'Disable installation', OT_SLUG )
		) );
	}

	function tweak() {
		OT_Helper::blockUserCap('install_themes');
	}
}