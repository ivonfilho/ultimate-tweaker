<?php

class OT_visual_composer_disable_frontend_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'visual_composer_disable_frontend', array(
			'title'   => __( 'Disable frontend editor', OT_SLUG ),
		) );
	}

	function tweak() {
		if(!defined( 'WPB_VC_VERSION' )) return;

		if(function_exists('vc_disable_frontend')) {
			vc_disable_frontend();
		}
	}
}