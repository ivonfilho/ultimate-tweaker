<?php

class OT_comment_remove_url_field_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'comment_remove_url_field', array(
			'title'    => __( 'Remove form url field', OT_SLUG ),
			'subtitle'    => __( '', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter('comment_form_default_fields', array($this, 'remove_comment_fields'));
	}

	function remove_comment_fields($fields) {
		unset($fields['url']);
		return $fields;
	}
}