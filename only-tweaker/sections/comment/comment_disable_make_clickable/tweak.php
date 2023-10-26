<?php

class OT_comment_disable_make_clickable_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'comment_disable_make_clickable', array(
			'title'    => __( 'Disable make_clickable', OT_SLUG )
		) );
	}

	function tweak() {
		remove_filter('comment_text', 'make_clickable', 9);
	}
}