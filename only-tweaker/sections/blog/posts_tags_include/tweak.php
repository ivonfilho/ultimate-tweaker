<?php

class OT_posts_tags_include_Tweak {
	function settings( ) {
		return OT_Helper::field( 'posts_tags_include', 'select', array(
			'title'    => __( 'Tags include', OT_SLUG ),
			'desc'    => __( '', OT_SLUG ),
			'data'     => 'tags',
			'multi'    => true,
		) );
	}

	function tweak() {
		add_filter('pre_get_posts', array($this, 'exclude'));
	}

	function exclude($query) {
		if ( $query->is_home ) {
			$query->set('tag__in', $this->value);}
		return $query;
	}
}