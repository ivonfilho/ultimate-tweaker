<?php

class OT_visual_composer_close_esc_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'visual_composer_close_esc', array(
			'title'    => __( 'Close windows on Esc', OT_SLUG )
		) );
	}

	function tweak() {
		if(!defined( 'WPB_VC_VERSION' )) return;

		add_action('vc_backend_editor_render', array($this, '_do'));
	}

	function _do() {
		OT_Helper::$_->script('vc-close-esc', __FILE__, array('deps' => array( 'jquery', 'post', 'mousetrap' )));
	}
}