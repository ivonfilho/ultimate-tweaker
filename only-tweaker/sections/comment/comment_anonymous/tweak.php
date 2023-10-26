<?php

class OT_comment_anonymous_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'comment_anonymous', array(
			'title'    => __( 'Allow anonymous comments', OT_SLUG ),
			'desc'    => __( 'Manually un-check "Comment author must fill out name and e-mail" Settings > Discussion', OT_SLUG )
		) );
	}

	function tweak() {
		add_filter('comment_form_default_fields', array($this, '_do'));
	}

	function _do($fields) {
		unset($fields['author']);
		unset($fields['email']);
		unset($fields['url']);
		return $fields;
	}
}