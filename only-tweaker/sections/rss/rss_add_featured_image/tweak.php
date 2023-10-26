<?php

class OT_rss_add_featured_image_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'rss_add_featured_image', array(
			'title'    => __( 'Add featured images', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter('the_content', array($this, '_do'));
	}

	function _do($content) {
		global $post;
		if( is_feed() ) {
			if ( has_post_thumbnail( $post->ID ) ){
				$output = get_the_post_thumbnail( $post->ID, 'medium', array( 'style' => 'float:right; margin:0 0 10px 10px;' ) );
				$content = $output . $content;
			}
		}
		return $content;
	}
}