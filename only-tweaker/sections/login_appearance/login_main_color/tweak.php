<?php

class OT_login_main_color_Tweak {
	function settings( ) {
		return OT_Helper::field( 'login_main_color', 'color', array(
			'title'    => __( 'Button & links color', OT_SLUG ),
			'desc'    => __( '', OT_SLUG ),
			'transparent'  => false,
		) );
	}

	function tweak() {
		add_action('login_head', array($this, '_do'));
	}

	function _do() {
		echo '<style type="text/css">';
		echo '.login #login #login_error a  { color: '.$this->value.'; }';
		echo '.login #login a:hover  { color: '.$this->value.'; }';
		echo '.login #login input[type=checkbox]:checked:before  { color: '.$this->value.'; }';
		echo '.login .button-primary  { background-color: '.$this->value.';border:none !important;box-shadow:none !important; }';
		echo '.login .button-primary:hover,.login .button-primary:active,.login .button-primary:focus ' .
		     '{ background-color: '.$this->value.';border:none;opacity:0.9; }';

		echo '.login #login input { box-shadow: 0 0 2px '.$this->value.';border:none; }';
        echo '.wp-core-ui .button { -webkit-box-shadow:none !important; -webkit-text-shadow:none !important;box-shadow:none !important; text-shadow:none !important; }';
		echo '</style>';
	}
}