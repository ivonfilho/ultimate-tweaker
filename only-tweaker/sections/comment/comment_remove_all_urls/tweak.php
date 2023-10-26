<?php

class OT_comment_remove_all_urls_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'comment_remove_all_urls', array(
			'title'    => __( 'Remove all urls', OT_SLUG ),
			'on_desc'    => __( 'All urls will be removed.', OT_SLUG ),
			'off_desc'    => __( 'All urls will be visible.', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter('comment_text', array($this, '_do'));
	}

	function _do($text) {
		$pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";
		$replacement = "";
		$text = preg_replace($pattern, $replacement, $text);
		return $text;
	}
}