<?php

class OT_content_link_target_blank_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'content_link_target_blank', array(
			'title'    => __( 'Open external post links in new window', OT_SLUG ),
			'desc'    => __( 'Adds target="_blank"', OT_SLUG ),
		) );
	}

	function tweak() {
		OT_Helper::$_->script('post-link-target-blank', __FILE__, array('deps' => array( 'jquery' )));
	}
}