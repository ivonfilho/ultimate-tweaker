<?php

class OT_theme_no_recent_comment_style_Tweak {
	function settings() {
		return OT_Helper::switcher( 'theme_no_recent_comment_style', array(
			'title'    => __( 'Remove recent comments widget styles', OT_SLUG )
		) );
	}

	function tweak() {
		add_filter( 'show_recent_comments_widget_style', '__return_false' );
	}
}