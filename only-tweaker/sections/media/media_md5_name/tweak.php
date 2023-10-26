<?php

class OT_media_md5_name_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'media_md5_name', array(
			'title'    => __( 'Rename files on upload', OT_SLUG ),
//			'desc'    => __( '', OT_SLUG ),
			'on_desc'    => __( "Name like '8d632bc0208111d6d8a435c210754eea.gif' will be used.", OT_SLUG ),
//			'off_desc'    => __( "Original file name.", OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter( 'wp_handle_upload_prefilter', array( $this, '_do' ) );
	}

	function _do($file) {
		if(function_exists('pathinfo')) {
			$path         = pathinfo( $file['name'] );
			$file['name'] = md5( $file['name'] ). "." . $path['extension'];
		}

		return $file;
	}
}