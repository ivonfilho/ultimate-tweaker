<?php

class OT_show_empty_category_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'show_empty_category',  array(
			'title'    => __( 'Show empty categories', OT_SLUG ),
			'desc'    => __( 'Need this if you wanna group by empty category.', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter( 'widget_categories_args', array( $this, '_do' ) );
	}

	function _do($cat_args) {
		$cat_args['hide_empty'] = 0;
		return $cat_args;
	}
}