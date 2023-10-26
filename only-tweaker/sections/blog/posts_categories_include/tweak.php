<?php

class OT_posts_categories_include_Tweak {
	function settings( ) {
		return OT_Helper::field( 'posts_categories_include', 'select', array(
			'title'    => __( 'Categories include', OT_SLUG ),
			'desc'    => __( '', OT_SLUG ),
			'data'     => 'categories',
			'multi'    => true,
		) );
	}

	function tweak() {
		add_filter('pre_get_posts', array($this, 'in_category'));
	}

	function in_category($query) {
		if ( $query->is_home ) {
			$query->set('category__in', $this->value);}
		return $query;
	}
}