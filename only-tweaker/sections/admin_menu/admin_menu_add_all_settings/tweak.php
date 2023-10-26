<?php

class OT_admin_menu_add_all_settings_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_menu_add_all_settings', array(
			'title'   => __( 'Add "All Settings" page', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action('admin_menu', array($this, '_do'), 0);
	}
	function _do() {
		add_options_page(__('All Settings'), __('All Settings'), 'manage_options', 'options.php');
	}
}