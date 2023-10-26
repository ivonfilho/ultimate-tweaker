<?php

class OT_theme_ms_navbuttoncolor_Tweak {
	function settings( ) {
		return OT_Helper::field( 'theme_ms_navbuttoncolor', 'color', array(
			'title'    => __( 'Navigation button color', OT_SLUG ),
			'desc'    => __( 'Custom color of the Back and Forward buttons in the Pinned site browser window.', OT_SLUG ),
			'transparent'  => false
		) );
	}

	function tweak() {
		add_action('wp_head', array($this, '_do'));
	}

	function _do() {
		?><meta name="msapplication-navbutton-color" content="<?php echo $this->value; ?>" /><?php
	}
}