<?php

class OT_post_min_word_count_Tweak {
	function settings( ) {
		$f = array();
		$f[] = OT_Helper::switcher( 'post_min_word_count', array(
			'title'       => __( 'Minimum post word count', OT_SLUG ),
		) );


		$f[] = OT_Helper::field( '_post_min_word_count_amount', 'slider', array(
			'required' => array( 'post_min_word_count', '=', '1' ),

			'right_title'    => __( 'Amount of words:', OT_SLUG ),

			'default'       => 10,
			'min'           => 2,
			'step'          => 1,
			'max'           => 100,
			'display_value' => 'text'
		) );

		return $f;
	}

	function tweak() {
		add_action( 'publish_post', array($this, '_do') );
	}

	function _do() {
		global $post;
		$num = (int) $this->options->_post_min_word_count_amount;
		$content = $post->post_content;
		if (str_word_count($content) < $num) {
			wp_die( __( 'Error: your post is below the minimum word count.', OT_SLUG ) );
		}
	}
}