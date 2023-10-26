<?php

class OT_post_autosave_off_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'post_autosave_off', array(
			'title'    => __( 'Disable auto-save', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action('wp_print_scripts', array($this, '_do'));
	}

	function _do() {
		wp_deregister_script('autosave');
		wp_register_script('autosave', false);
	}
}