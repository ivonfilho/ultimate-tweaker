<?php

class OT_media_post_no_img_p_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'media_post_no_img_p', array(
			'title'    => htmlspecialchars(__( 'Remove <p> tags from around images', OT_SLUG )),
		) );
	}

	function tweak() {
		add_filter('the_content', array($this, '_do'));
	}

	function _do($content) {
		return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	}
}