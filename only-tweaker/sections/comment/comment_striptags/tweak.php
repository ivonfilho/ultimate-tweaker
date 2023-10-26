<?php

class OT_comment_striptags_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'comment_striptags', array(
			'title'    => __( 'Strip all tags', OT_SLUG ),
			'on_desc'    => __( 'All tags will be deleted', OT_SLUG ),
			'off_desc'    => __( 'All tags will be visible', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter('comment_text', 'strip_tags');
	}
}