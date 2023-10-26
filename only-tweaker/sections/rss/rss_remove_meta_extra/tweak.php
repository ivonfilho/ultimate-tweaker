<?php

class OT_rss_remove_meta_extra_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'rss_remove_meta_extra', array(
			'title'    => __( 'Extra feeds such as category feeds', OT_SLUG ),
			'on_desc'    => __( '<strike>&lt;link rel="alternate" type="application/rss+xml" title="Category Feed" href="http://wp/?feed=rss2&#038;cat=1" /></strike> in &lt;head>.', OT_SLUG ),
			'off_desc'    => __( '&lt;link rel="alternate" type="application/rss+xml" title="Category Feed" href="http://wp/?feed=rss2&#038;cat=1" /> in &lt;head>.', OT_SLUG ),
		) );
	}

	function tweak() {
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}
}