<?php

class OT_admin_no_icons_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_no_icons', array(
			'title'   => __( 'Hide all icons', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action('admin_head', array($this, '_do'), 0);
	}
	function _do() {
		?><style>.wp-menu-image {display: none;} .wp-menu-name {margin-left: 12px;} .folded .wp-menu-image {display: block;} .folded .wp-menu-name {margin-left: 0px;} </style><?php
	}
}