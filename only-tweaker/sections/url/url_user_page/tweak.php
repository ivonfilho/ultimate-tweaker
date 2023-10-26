<?php

class OT_url_user_page_Tweak {
	function settings( ) {
		return OT_Helper::field( 'url_user_page', 'select', array(
			'title'       => __( 'Author Link', OT_SLUG ),
			'desc'       => __( 'By default, link goes to author posts, you can define your own page.', OT_SLUG ),
			'data'     => 'pages',
		) );
	}

    function isAvailable() {
        return !in_array( 'ultimate-member/index.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
    }

	function tweak() {
		add_action( 'author_link', array($this, '_do') );
	}

	function _do() {
//		var_dump($this->value);
		return get_page_link($this->value);
	}
}