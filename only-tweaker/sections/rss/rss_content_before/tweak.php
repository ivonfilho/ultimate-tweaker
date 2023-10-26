<?php

class OT_rss_content_before_Tweak {
	function settings( ) {
		return OT_Helper::field( 'rss_content_before', 'textarea', array(
			'title'       => __( 'Custom Head for Rss', OT_SLUG ),
			'placeholder' => __( 'Example: Ads', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action( 'the_content', array($this, '_do') );
	}

	function _do($content) {
		if(is_feed()){
			$content = $this->value . $content;
		}
		return $content;
	}
}