<?php

class OT_admin_no_menu_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_no_menu', array(
			'title'   => __( 'Hide left panel entirely', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action('admin_head', array($this, '_do'), 0);
	}
	function _do() {
		?><style>#adminmenuback,#adminmenuwrap {display:none} #wpcontent, #wpfooter { margin-left: 0px; } </style><?php
	}
}