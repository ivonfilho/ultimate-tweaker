<?php

class OT_comment_min_length_Tweak {
	function settings( ) {
		$f = array();
		$f[] = OT_Helper::switcher( 'comment_min_length', array(
			'title'   => __( 'Check min length', OT_SLUG )
		) );

		$f[] = OT_Helper::field( '_comment_min_length_num', 'slider', array(
			'required' => array( 'comment_min_length', '=', '1' ),

			'title'    => (__( 'Min length', OT_SLUG )),
			'default'       => 20,
			'min'           => 1,
			'step'          => 1,
			'max'           => 100,
			'display_value' => 'label'
		) );

		return $f;
	}

	function tweak() {
		add_action( 'preprocess_comment', array($this, '_do') );
	}

	function _do( $commentdata ) {
		$minimalCommentLength = isset($this->options->_comment_min_length_num) ? (int) $this->options->_comment_min_length_num : 20;

		if ( strlen( trim( $commentdata['comment_content'] ) ) < $minimalCommentLength )
		{
			wp_die( sprintf(__('All comments must be at least %s characters long.', OT_SLUG), $minimalCommentLength));
		}
		return $commentdata;
	}
}