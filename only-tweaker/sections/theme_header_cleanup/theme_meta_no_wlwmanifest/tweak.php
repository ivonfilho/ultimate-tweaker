<?php

class OT_theme_meta_no_wlwmanifest_Tweak {
	function settings() {
		return OT_Helper::switcher( 'theme_meta_no_wlwmanifest', array(
			'title'    => __( 'Remove wlwmanifest', OT_SLUG ),
			'desc'     => __( 'Used in Windows Live Writer client.', OT_SLUG ),
			'on_desc'  => __( '<strike>&lt;link rel="wlwmanifest" type="application/wlwmanifest+xml" href="http://wp/wp-includes/wlwmanifest.xml" /></strike> in &lt;head>.', OT_SLUG ),
			'off_desc' => __( '&lt;link rel="wlwmanifest" type="application/wlwmanifest+xml" href="http://wp/wp-includes/wlwmanifest.xml" />  in &lt;head>.', OT_SLUG ),
		) );
	}

	function tweak() {
		remove_action( 'wp_head', 'wlwmanifest_link' );
	}
}