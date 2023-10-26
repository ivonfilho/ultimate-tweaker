<?php

class OT_admin_disable_tmce_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_disable_tmce', array(
			'title'   => __( 'Disable TinyMCE editor', OT_SLUG )
		) );
	}

	function tweak() {
		add_filter ( 'user_can_richedit' , create_function ( '$a' , 'return false;' ) , 50 );
	}
}