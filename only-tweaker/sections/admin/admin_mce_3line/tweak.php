<?php

class OT_admin_mce_3line_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_mce_3line', array(
			'title'   => __( 'Additional TinyMCE buttons', OT_SLUG ),
			'desc'   => __( 'Additional buttons font select, size, style, del, subscript, superscript cleanup will be shown.', OT_SLUG )
		) );
	}

	function tweak() {
		add_filter( 'mce_buttons_3', array($this, '_do') );
	}

	function _do($buttons) {
		$buttons[] = 'fontselect';
		$buttons[] = 'fontsizeselect';
		$buttons[] = 'styleselect';
		$buttons[] = 'del';
		$buttons[] = 'subscript';
		$buttons[] = 'superscript';
		$buttons[] = 'cleanup';
		return $buttons;
	}
}