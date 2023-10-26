<?php

class OT_general_title_wptexturize_no_Tweak {
	function settings() {
		return OT_Helper::switcher(
			'general_title_wptexturize_no',
			array(
				'title'    => __( 'Disable "wptexturize" function for page title', OT_SLUG ),
				'desc'  =>
					__( 'This function applies transformations of quotes to smart quotes, apostrophes, dashes, ellipses, the trademark symbol, and the multiplication symbol.', OT_SLUG ) .
					sprintf(__('You can read information about this function <a href="%s" target="_blank">here</a>', OT_SLUG), 'http://codex.wordpress.org/Function_Reference/wptexturize'),
		) );

	}

	function tweak() {
		remove_filter( "the_title", "wptexturize" );
	}
}