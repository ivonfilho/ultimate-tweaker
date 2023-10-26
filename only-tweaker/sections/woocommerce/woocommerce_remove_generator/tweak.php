<?php

class OT_woocommerce_remove_generator_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'woocommerce_remove_generator', array(
			'title'    => __( 'Remove WooCommerce generator tag', OT_SLUG ),
			'on_desc'    => __( '<strike>&lt;meta name="generator" content="WooCommerce 2.x" /></strike> in &lt;head>.', OT_SLUG ),
			'off_desc'    => htmlentities(__( '<meta name="generator" content="WooCommerce 2.x" /> in <head>.', OT_SLUG )),
		) );
	}

	function tweak() {
		if(@$this->options->custom_generator) return;

		$filters = array(
			'get_the_generator_html',
			'get_the_generator_xhtml'
		);

		foreach ( $filters as $filter ) {
			remove_filter( $filter, 'wc_generator_tag' );
		}
	}
}