<?php

class OT_security_self_pinging_disable_Tweak {
	function settings() {
		return OT_Helper::switcher( 'security_self_pinging_disable', array(
			'title'   => __( 'Disable Self Pingbacks', OT_SLUG ),
			'on_desc' => __( 'Pingbacks are disabled', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action( 'pre_ping', array(&$this, '_do') );
	}

	function _do( &$links ) {
		$home = get_option( 'home' );
		foreach ( $links as $l => $link ) {
			if ( 0 === strpos( $link, $home ) ) {
				unset( $links[ $l ] );
			}
		}
	}
}