<?php

class OT_content_code_Tweak {
	function settings( ) {
		return OT_Helper::field( 'content_code', 'textarea', array(
			'title'       => __( 'Custom After Post code', OT_SLUG ),
//			'subtitle'       => __( 'Code will be added to the and of each post and page', OT_SLUG ),
			'desc'       => __( 'Code will be added to the and of each post and page', OT_SLUG )
							. '<br/>'
							. __( 'Shortcodes are supported', OT_SLUG )
		) );
	}

	function tweak() {
		add_action( 'the_content', array($this, '_do') );
	}

	function _do($content) {
		if(!is_feed() && !is_home()) {
			$content.= $this->value;
		}
		return $content;
	}
}