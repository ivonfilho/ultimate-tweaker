<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

define('UT_SLUG', 'ultimate-tweaker2');

class UT_Uninstaller {
	static function resetAllSettings() {
		$settings = array(UT_SLUG);

		foreach($settings as $setting) {
			delete_option( $setting );
			delete_option( $setting . '-transients' );
		}
	}
}

UT_Uninstaller::resetAllSettings();