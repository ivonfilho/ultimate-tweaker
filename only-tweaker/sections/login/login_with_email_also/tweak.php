<?php

class OT_login_with_email_also_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'login_with_email_also', array(
			'title'    => __( 'Enable login with Email', OT_SLUG ),
			'on_desc'    => __( 'Users can login by login & email', OT_SLUG ),
			'off_desc'    => __( 'Users can login by login only', OT_SLUG )
		) );

	}

	function tweak() {
		if($this->value && (@$_REQUEST['action'] !== 'register')) {
			add_filter('gettext', array($this, '_do'));
			add_action('wp_authenticate', array($this, '_doLogin'));
		}
	}

	function _do($text){
		if(in_array($GLOBALS['pagenow'], array('wp-login.php'))){
			if ($text == 'Username'){
				$text = __('Username / Email', OT_SLUG);
			}
		}
		return $text;
	}

	function _doLogin($username) {
		$user = get_user_by('email', $username);
		if(!empty($user->user_login))
			$username = $user->user_login;

		return $username;
	}
}