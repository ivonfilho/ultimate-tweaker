<?php

class OT_theme_meta_no_prev_next_Tweak {
	function settings() {
		return OT_Helper::switcher( 'theme_meta_no_prev_next', array(
			'title'    => __( 'Remove the prev and next post link', OT_SLUG ),
			'on_desc'  => __( "<strike>&lt;link rel='prev' title='Hello world!' href='http://wp/?p=1' /></strike> in &lt;head>.", OT_SLUG ),
			'off_desc' => __( "&lt;link rel='prev' title='Hello world!' href='http://wp/?p=1' />  in &lt;head>.", OT_SLUG ),
		) );
	}

	function tweak() {
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

		//OLD
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	}
}