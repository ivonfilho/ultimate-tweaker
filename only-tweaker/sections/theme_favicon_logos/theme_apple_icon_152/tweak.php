<?php

class OT_theme_apple_icon_152_Tweak {
	function settings() {
		return OT_Helper::field( 'theme_apple_icon_152', 'media', array(
			'url'   => true,
			'mode'  => false,
			'title' => __( 'iPad Retina', OT_SLUG ),
			'desc'  => __( 'Normal size is 152x152px.', OT_SLUG )
		) );
	}

	function tweak() {
		add_action( 'wp_head', array( $this, '_do' ) );
	}

	function _do() {
		?>
		<link rel="apple-touch-icon" sizes="152x152" href="<?php echo $this->value['url']; ?>" /><?php
	}
}