<?php

class OT_woocommerce_remove_feed_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'woocommerce_remove_feed', array(
			'title'    => __( 'Remove feed', OT_SLUG ),
		) );
	}

	function tweak() {
		remove_action( 'wp_head', 'wc_products_rss_feed' );
	}
}