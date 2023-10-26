<?php

class OT_content_wpautop_no_Tweak {
	function settings() {
		return OT_Helper::switcher( 'content_wpautop_no', array(
			'title'    => __( 'Disable content wpautop', OT_SLUG ),
		) );
	}

	function tweak() {
		remove_filter( "the_content", "wpautop" );
		remove_filter( "the_excerpt", "wpautop" );
	}
}