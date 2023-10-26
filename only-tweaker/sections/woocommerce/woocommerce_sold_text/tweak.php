<?php

class OT_woocommerce_sold_text_Tweak {
	function settings() {
		$f = array();

		$f[] = OT_Helper::switcher( 'woocommerce_sold_text', array(
			'title' => sprintf(__( 'Change "%s" message', OT_SLUG ), __( 'Out of stock', 'woocommerce' ))
		) );

		$f[] = OT_Helper::field( '_woocommerce_sold_text_message', 'text', array(
			'required' => array( 'woocommerce_sold_text', '=', '1' ),
			'right_title'    => __( 'Message:', OT_SLUG ),
			'default'  => __( 'Sold', OT_SLUG )
		) );

		return $f;
	}

	function tweak() {
		add_filter( 'woocommerce_get_availability', array( &$this, '_do' ));
	}

	function _do( $availability ) {
		$availability['availability'] = str_ireplace(__( 'Out of stock', 'woocommerce' ), $this->options->_woocommerce_sold_text_message,
			$availability['availability']);
		return $availability;
	}
}