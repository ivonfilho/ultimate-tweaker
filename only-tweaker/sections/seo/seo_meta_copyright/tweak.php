<?php

class OT_seo_meta_copyright_Tweak {
	function settings() {
		$f   = array();

		$f[] = OT_Helper::field( 'seo_meta_copyright', 'text', array(
			'title'       => __( 'Meta copyright', OT_SLUG ),
			'placeholder' => __( 'Example: All rights reserved © Amino, 2016', OT_SLUG ),
		) );

		$f[] = OT_Helper::field( '_seo_meta_copyright_mode', 'radio', array(
			'title'   => __( 'Meta Copyright Pages', OT_SLUG ),
			'options' => array(
				''         => 'All pages',
				'singular' => 'Singular'
			),
		) );

		return $f;
	}

	function tweak() {
		add_action( 'wp_head', array( $this, '_do' ) );
	}

	function _do() {
		if ( $this->options->_seo_meta_copyright_mode == 'singular' && ! is_singular() ) {
			return;
		}

		echo "<meta name=\"copyright\" content=\"" . wptexturize( $this->value ) . "\">";
	}
}