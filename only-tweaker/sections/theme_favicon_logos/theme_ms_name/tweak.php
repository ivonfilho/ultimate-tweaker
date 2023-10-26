<?php

class OT_theme_ms_name_Tweak {
	function settings( ) {
		return OT_Helper::field( 'theme_ms_name', 'text', array(
			'title'       => __( 'Application name', OT_SLUG )
		) );
	}

	function tweak() {
		add_action( 'wp_head', array($this, '_do') );
	}

	function _do() {
		echo "<meta name=\"application-name\" content=\"". wptexturize($this->value) ."\">";
	}
}