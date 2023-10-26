<?php

class OT_theme_ms_tileimage_Tweak {
	function settings() {
		return OT_Helper::field( 'theme_ms_tileimage', 'media', array(
			'url'   => true,
			'mode'  => false,
			'title' => __( 'Tile image', OT_SLUG ),
			'desc'  => __( 'Background image for live tile.', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action( 'wp_head', array( $this, '_do' ) );
	}

	function _do() {
		?>
		<meta name="msapplication-TileImage" content="<?php echo $this->value['url']; ?>" /><?php
	}
}