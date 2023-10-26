<?php

class OT_content_make_clickable_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'content_make_clickable', array(
			'title'    => __( 'Make content urls clickable', OT_SLUG ),
			'desc'    => __( 'Convert urls in content to links', OT_SLUG ),
			'on_desc'    => __( 'Convert http://site.com to &lta href="http://site.com" rel="nofollow" target="_blank">http://site.com</a>', OT_SLUG )
		) );
	}

	function tweak() {
		add_filter("the_content", 'make_clickable');
		add_filter("the_excerpt", 'make_clickable');
	}
}