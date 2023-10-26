<?php

class OT_theme_meta_no_shortlink_Tweak {
	function settings() {
		return OT_Helper::switcher( 'theme_meta_no_shortlink', array(
			'title'    => __( 'Remove shortlink for the page', OT_SLUG ),
			'on_desc'  => __( "<strike>&lt;link rel='shortlink' href='http://wp/?p=1' /></strike> in &lt;head>.", OT_SLUG ),
			'off_desc' => __( "&lt;link rel='shortlink' href='http://wp/?p=1' />  in &lt;head>.", OT_SLUG ),
		) );
	}

	function tweak() {
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
	}
}