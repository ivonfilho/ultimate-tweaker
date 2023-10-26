<?php

class OT_rss_remove_meta_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'rss_remove_meta', array(
			'title'    => __( 'Remove the standard feed links', OT_SLUG ),
			'desc'     => __( 'Remove meta tags from header, do not turn off.', OT_SLUG ),
			'on_desc'    => __( '<strike>&lt;link rel="alternate" type="application/rss+xml" title="tweaker &raquo; Feed" href="http://wp/?feed=rss2" /><br/>
&lt;link rel="alternate" type="application/rss+xml" title="tweaker &raquo; Comments Feed" href="http://wp/?feed=comments-rss2" /></strike> in &lt;head>.', OT_SLUG ),
			'off_desc'    => __( '&lt;link rel="alternate" type="application/rss+xml" title="tweaker &raquo; Feed" href="http://wp/?feed=rss2" /><br/>
&lt;link rel="alternate" type="application/rss+xml" title="tweaker &raquo; Comments Feed" href="http://wp/?feed=comments-rss2" /> in &lt;head>.', OT_SLUG ),
		) );
	}

	function tweak() {
		remove_action( 'wp_head', 'feed_links', 2 );
	}
}