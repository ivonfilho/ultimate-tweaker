<?php

class OT_custom_code_footer_Tweak {
	function settings( ) {
		return OT_Helper::field( 'custom_code_footer', 'textarea', array(
			'title'       => __( 'Custom Footer or Tracking code', OT_SLUG ),
			'placeholder' => __( 'Example: <script>custom_code();</script>', OT_SLUG ),
			'desc'        => __( 'Paste other tracking code here. This code will be added before the closing body tag.', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action( 'wp_footer', array($this, '_do'), 10000 );
	}

	function _do() {
		echo $this->value;
	}
}