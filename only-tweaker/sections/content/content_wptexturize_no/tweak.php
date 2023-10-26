<?php

class OT_content_wptexturize_no_Tweak {
	function settings() {
		return OT_Helper::switcher( 'content_wptexturize_no', array(
			'title'    => __( 'Disable content wptexturize', OT_SLUG ),
			'desc'  => 'http://codex.wordpress.org/Function_Reference/wptexturize',
		) );
	}

	function tweak() {
		remove_filter( "the_content", "wptexturize" );
		remove_filter( "the_excerpt", "wptexturize" );
	}
}