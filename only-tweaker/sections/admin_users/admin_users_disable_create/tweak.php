<?php

class OT_admin_users_disable_create_Tweak {
	function settings() {
		return OT_Helper::switcher( 'admin_users_disable_create', array(
			'title' => __( 'Disable creation new', OT_SLUG )
		) );
	}

	function tweak() {
		OT_Helper::blockUserCap('create_users');
	}
}