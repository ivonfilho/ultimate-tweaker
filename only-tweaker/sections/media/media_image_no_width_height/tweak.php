<?php

class OT_media_image_no_width_height_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'media_image_no_width_height', array(
			'title'   => __( 'Remove width & height attributes', OT_SLUG ),
			'desc'   => __( 'Automatically added attributes will be removed from HTML tag', OT_SLUG )
		) );
	}

	function tweak() {
		add_filter( 'post_thumbnail_html', array($this, '_do'), 10 );
		add_filter( 'image_send_to_editor', array($this, '_do'), 10 );
	}

	function _do( $html ) {
		$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
		return $html;
	}
}