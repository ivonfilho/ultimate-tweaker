<?php

class OT_theme_ms_150x150_Tweak {
	function settings() {
		return OT_Helper::field( 'theme_ms_150x150', 'media', array(
			'url'   => true,
			'mode'  => false,
			'title' => __( 'Medium tile', OT_SLUG ),
			'desc'  => __( 'Normal size is 150x150px.', OT_SLUG )
		) );
	}

	function tweak() {
		add_action( 'wp_head', array( $this, '_do' ) );
	}

	function _do() {
		?>
		<meta name="msapplication-square150x150logo" content="<?php echo $this->value['url']; ?>" /><?php
	}
}