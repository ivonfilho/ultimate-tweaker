<?php

class OT_security_meta_no_rsd_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'security_meta_no_rsd', array(
			'title'    => __( 'Remove Really Simple Discovery tag', OT_SLUG ),
			'desc'    => __( 'Used by different desktop and online blog clients. If you do not use external visual editors, you can enable this tweak.', OT_SLUG ),
			'on_desc'    => __( '<strike>&lt;link rel="EditURI" type="application/rsd+xml" title="RSD" href="http://wp/xmlrpc.php?rsd" /></strike> in &lt;head>.', OT_SLUG ),
			'off_desc'    => __( '&lt;link rel="EditURI" type="application/rsd+xml" title="RSD" href="http://wp/xmlrpc.php?rsd" /> in &lt;head>.', OT_SLUG ),
		) );

	}

	function tweak() {
		remove_action('wp_head', 'rsd_link');
	}
}