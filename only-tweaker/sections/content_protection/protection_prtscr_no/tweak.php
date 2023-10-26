<?php

class OT_protection_prtscr_no_Tweak {
	function settings() {
		return OT_Helper::switcher( 'protection_prtscr_no', array(
			'title' => __( 'Disable PrintScreen button', OT_SLUG )
		) );
	}

	function tweak() {
		OT_Helper::$_->script('dis-prtscr', __FILE__, array('deps'=>array("jquery")) );
	}
}