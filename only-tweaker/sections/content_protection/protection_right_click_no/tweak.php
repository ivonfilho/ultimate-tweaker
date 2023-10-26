<?php

class OT_protection_right_click_no_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'protection_right_click_no', array(
			'title'    => __( 'Disable right-click', OT_SLUG )
		) );
	}

	function tweak() {
		OT_Helper::$_->script('dis-right-click', __FILE__, array('deps'=>array("jquery")) );
	}
}
