<?php

class OT_theme_favicon_Tweak {
	function settings() {
		return OT_Helper::field( 'theme_favicon', 'media', array(
			'url'   => true,
			'title' => __( 'Favicon', OT_SLUG ),
//			'desc'    => __( '', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action( 'wp_head', array( $this, '_do' ) );
	}

	function _do() {
		?>
		<link rel="shortcut icon" href="<?php echo $this->value['url']; ?>" /><?php
	}
}