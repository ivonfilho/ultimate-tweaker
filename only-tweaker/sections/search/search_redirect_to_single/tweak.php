<?php

class OT_search_redirect_to_single_Tweak {
	function settings() {
		return OT_Helper::switcher( 'search_redirect_to_single', array(
			'title'    => __( 'Redirect to single result', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action( 'template_redirect', array( $this, '_do' ) );
	}

	function _do( ) {
		if (is_search()) {
			global $wp_query;
			if ($wp_query->post_count == 1) {
				wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
			}
		}
	}
}