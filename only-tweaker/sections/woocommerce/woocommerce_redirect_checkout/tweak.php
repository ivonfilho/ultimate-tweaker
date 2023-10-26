<?php

class OT_woocommerce_redirect_checkout_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'woocommerce_redirect_checkout', array(
			'title'    => __( 'Redirect to checkout after adding to cart', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter( 'add_to_cart_redirect', array(&$this, '_do'),0 );
	}

	function _do($checkoOT_url) {
		global $woocommerce;
		$checkoOT_url = $woocommerce->cart->get_checkout_url();
		return $checkoOT_url;
	}
}