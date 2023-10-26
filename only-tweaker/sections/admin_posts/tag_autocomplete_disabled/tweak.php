<?php

class OT_tag_autocomplete_disabled_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'tag_autocomplete_disabled', array(
			'title'    => __( 'Disable tag Autocomplete in Posts', OT_SLUG )
		) );
	}

	function tweak() {
		OT_Helper::$_->script('tag-autocomplete-disabled', __FILE__, array('deps' => array( 'jquery', 'post' )));
	}
}