<?php

class OT_admin_users_filter_hide_Tweak {
	function settings() {

		return OT_Helper::switcher( 'admin_users_filter_hide', array(
			'title' => __( 'Hide filter', OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter( "views_users", array( $this, '_views_users' ), 10000 );
	}


	function _views_users($views)
	{
		return null;
	}
}