<?php

class OT_theme_smooth_scroll_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'theme_smooth_scroll',array(
			'title'       => __( 'Enable smooth scroll in Chrome', OT_SLUG )
		) );
	}

	function tweak() {
		OT_Helper::$_->script('smooth-scroll', __FILE__, array('deps' => array( 'jquery' )));
	}
}