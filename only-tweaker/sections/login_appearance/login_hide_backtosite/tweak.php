<?php

class OT_login_hide_backtosite_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'login_hide_backtosite', array(
			'title'    => __( 'Hide "Back to blog" link', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter('login_head', array($this, '_do'));
	}

	function _do() {
		echo '<style type="text/css">';
		echo '.login #backtoblog { display:none; }';
		echo '</style>';
	}
}