<?php

class OT_url_change_user_slug_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'url_change_user_slug', array(
			'title'   => __( 'Change url of author to user', OT_SLUG ),
			'on_desc'   => __( 'http://wp/user/username/', OT_SLUG ),
			'off_desc'   => __( 'http://wp/author/username/', OT_SLUG )
		) );
	}

	function isAvailable() {
	    return !in_array( 'ultimate-member/index.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
    }

	function tweak() {
//		$this->_do();
			add_action( 'init', array($this, '_do') );
	}

	function _do() {
		global $wp_rewrite;
		$author_slug = 'user';
		$wp_rewrite->author_base = $author_slug;
		/** TODO: chache */
		if(method_exists($wp_rewrite, 'flush_rules'))
			$wp_rewrite->flush_rules();
	}
}