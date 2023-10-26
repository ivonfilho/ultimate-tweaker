<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

//TODO: move to register_uninstall_hook
define('AMT_SLUG', 'admin-menu-tweaker');

class AMT_Uninstaller {
	static function resetAllSettings() {
		$settings = array(AMT_SLUG);

		foreach($settings as $setting) {
			delete_option( $setting );
			delete_option( $setting . '-transients' );
		}
	}
}

AMT_Uninstaller::resetAllSettings();