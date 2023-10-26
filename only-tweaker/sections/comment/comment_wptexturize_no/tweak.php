<?php

class OT_comment_wptexturize_no_Tweak {
	function settings() {
		return OT_Helper::switcher( 'comment_wptexturize_no', array(
			'title' => __( 'Disable wptexturize', OT_SLUG ),
			'desc'  => 'You can read information about this function here: http://codex.wordpress.org/Function_Reference/wptexturize',
		) );
	}

	function tweak() {
		remove_filter( "comment_text", "wptexturize" );
	}
}