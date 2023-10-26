<?php

class OT_theme_fast_click_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'theme_fast_click',array(
			'title'       => __( 'Enable fast click on Touch-devices', OT_SLUG )
		) );
	}

	function tweak() {
		OT_Helper::$_->script('fastclick', __FILE__, array('deps' => array( 'jquery' )));
	}
}