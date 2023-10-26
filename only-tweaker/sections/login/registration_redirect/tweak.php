<?php

class OT_registration_redirect_Tweak {
	function settings( ) {
		return OT_Helper::field( 'registration_redirect', 'select', array(
			'title'       => __( 'Redirect', OT_SLUG ),
			'desc'       => __( 'By default, user will be redirected to admin page, or your own.', OT_SLUG ),
			'data'     => 'pages',
		) );
	}

	function tweak() {
		add_action( 'registration_redirect', array($this, '_do') );
	}

	function _do() {
//		var_dump($this->value);
		return get_page_link($this->value);
	}
}