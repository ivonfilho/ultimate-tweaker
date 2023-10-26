<?php

class OT_admin_users_disable_list_Tweak {
	function settings() {
		return OT_Helper::switcher( 'admin_users_disable_list', array(
			'title' => __( 'Disable list users', OT_SLUG )
		) );
	}

	function tweak() {
		OT_Helper::blockUserCap('list_users');
		add_action('admin_menu', array($this, '_do'), 50);
	}

	function _do() {
		remove_submenu_page('profile.php', 'user-new.php');
	}
}