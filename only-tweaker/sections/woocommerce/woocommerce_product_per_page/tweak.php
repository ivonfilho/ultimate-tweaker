<?php

class OT_woocommerce_product_per_page_Tweak {
	function settings( ) {
		$f = array();
		$f[] = OT_Helper::switcher( 'woocommerce_product_per_page', array(
			'title'       => __( 'Set number of products displayed per page', OT_SLUG ),
		) );


		$f[] = OT_Helper::field( '_woocommerce_product_per_page_amount', 'slider', array(
			'required' => array( 'woocommerce_product_per_page', '=', '1' ),

			'title'    => __( 'Amount of products', OT_SLUG ),

			'default'       => 10,
			'min'           => 2,
			'step'          => 1,
			'max'           => 80,
			'display_value' => 'text'
		) );

		return $f;
	}

	function tweak() {
		add_filter( 'loop_shop_per_page', create_function( '$cols', 'return '.(int) $this->options->_woocommerce_product_per_page_amount.';' ), 20 );
	}
}