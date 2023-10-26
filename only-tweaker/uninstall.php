<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

define('AMT_SLUG', 'only-tweaker');

class OT_Uninstaller {
	static function resetAllSettings() {
		$settings = array(OT_SLUG);

		foreach($settings as $setting) {
			delete_option( $setting );
			delete_option( $setting . '-transients' );
		}
	}
}

OT_Uninstaller::resetAllSettings();