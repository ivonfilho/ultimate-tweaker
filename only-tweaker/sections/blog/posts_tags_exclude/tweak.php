<?php

class OT_posts_tags_exclude_Tweak {
	function settings( ) {
		return OT_Helper::field( 'posts_tags_exclude', 'select', array(
			'title'    => __( 'Tags exclude', OT_SLUG ),
			'desc'    => __( '', OT_SLUG ),
			'data'     => 'tags',
			'multi'    => true
		) );
	}

	function tweak() {
		add_filter('pre_get_posts', array($this, 'exclude'));
	}

	function exclude($query) {
		if ( $query->is_home ) {
			$query->set('tag__not_in', $this->value);}
		return $query;
	}
}