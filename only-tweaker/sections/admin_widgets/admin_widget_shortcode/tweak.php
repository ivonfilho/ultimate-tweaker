<?php

class OT_admin_widget_shortcode_Tweak {
	function settings() {
		return OT_Helper::switcher( 'admin_widget_shortcode', array(
			'title' => __( 'Enable shortcodes in Text Widgets', OT_SLUG )
		) );
	}

	function tweak() {
		add_filter( 'widget_text', 'do_shortcode' );
	}
}