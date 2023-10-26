<?php

class OT_protection_img_drag_no_Tweak {
	function settings() {
		return OT_Helper::switcher( 'protection_img_drag_no', array(
			'title' => __( 'Disable image dragging', OT_SLUG ),
			'desc' => __( '2 methods CSS and JavaScript.', OT_SLUG )
		) );
	}

	function tweak() {
		OT_Helper::$_->script('dis-img-drag', __FILE__, array('deps'=>array("jquery")) );
		add_action( 'wp_head', array( $this, '_do' ) );
	}

	function _do() {
		global $wp_styles;
//		$ref = @$wp_styles->queue[0];
		echo '<style>' . '*,*:focus {-webkit-user-drag: none;}' . '</style>';
//		wp_add_inline_style( $ref, '*,*:focus {-webkit-user-drag: none;}' );
	}
}