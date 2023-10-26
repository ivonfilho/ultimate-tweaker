<?php

class OT_admin_plugins_disable_install_Tweak {
	function settings() {
		return OT_Helper::switcher( 'admin_plugins_disable_install', array(
			'title' => __( 'Disable installation', OT_SLUG )
		) );
	}

	function tweak() {
		OT_Helper::blockUserCap('install_plugins');
	}
}