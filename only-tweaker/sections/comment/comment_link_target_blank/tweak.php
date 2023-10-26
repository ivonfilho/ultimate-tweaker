<?php

class OT_comment_link_target_blank_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'comment_link_target_blank', array(
			'title'    => __( 'Open external comment links in new window', OT_SLUG ),
			'desc'    => __( 'Adds target="_blank"', OT_SLUG )
		) );
	}

	function tweak() {
		OT_Helper::$_->script('comment-link-target-blank', __FILE__, array('deps' => array( 'jquery' )));
	}
}