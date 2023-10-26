<?php

class OT_admin_appearance_menu_bg_color_Tweak {
	function settings() {
		$f = array();

		$f[] = OT_Helper::field( 'admin_appearance_menu_bg_color', 'color', array(
			'title' => __( 'Menu background color', OT_SLUG ),
		) );

		return $f;
	}

	function tweak() {
		add_action( 'admin_head', array( $this, '_do' ), 1 );
	}

	function _do() {
		echo '<style type="text/css">' .
		     '#adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap { background-color:'.$this->value.' !important }' .
		     '</style>';
	}
}