<?php

class OT_woocommerce_categories_exclude_Tweak {
	function settings( ) {
		return OT_Helper::field( 'woocommerce_categories_exclude', 'select', array(
			'title'    => __( 'Categories exclude', OT_SLUG ),
			'desc'    => __( '', OT_SLUG ),
			'data'     => 'terms',
			'args' => array('taxonomies'=>'product_cat', 'args'=>array()),
			'multi'    => true,
			'default'  => ''
		) );
	}

	function tweak() {
//			add_filter('pre_get_posts', array($this, 'exclude_category'));
	}

	function exclude_category($query) {
		if ( is_shop() ) {
			//var_dump($this->value);
			$query->set( 'tax_query', array(array(
				'taxonomy' => 'product_cat',
				'field' => 'slug',
				'terms' => $this->value,
				'operator' => 'NOT IN'
			)));
		}
		return $query;
	}
}