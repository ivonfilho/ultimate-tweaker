<?php

class OT_theme_meta_no_canonical_Tweak {
	function settings() {
		return OT_Helper::switcher( 'theme_meta_no_canonical', array(
			'title'    => __( 'Remove canonical link for the page', OT_SLUG ),
			'subtitle' => __( '', OT_SLUG ),
			'on_desc'  => __( "<strike>&lt;link rel='canonical' href='http://wp/?p=1' /></strike> in &lt;head>.", OT_SLUG ),
			'off_desc' => __( "&lt;link rel='canonical' href='http://wp/?p=1' />  in &lt;head>.", OT_SLUG ),
		) );
	}

	function tweak() {
		remove_action( 'wp_head', 'rel_canonical' );
	}
}