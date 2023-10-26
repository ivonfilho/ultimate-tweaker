<?php

class OT_woocommerce_remove_ordering_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'woocommerce_remove_ordering', array(
			'title'    => __( 'Remove ordering', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action( 'init', array(&$this, '_do') );
	}

	function _do() {
		remove_all_actions( 'woocommerce_before_shop_loop');
	}
}