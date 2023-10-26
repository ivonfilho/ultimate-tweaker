<?php

class OT_content_twitter_name_link_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'content_twitter_name_link', array(
			'title'    => __( 'Convert Twitter name to links in content', OT_SLUG ),
			'desc'    => __( 'Automatically converts @username in content to link to twitter account page', OT_SLUG ),
			'on_desc'    => __( "@username will be converted to &lt;a href=\"http://twitter.com/username\"" .
			                    " target=\"_blank\" rel=\"nofollow\">@username&lt;/a>", OT_SLUG ),
		) );
	}

	function tweak() {
		add_filter('the_content', array($this, '_do'));
		add_filter('comment_text', array($this, '_do'));
	}

	function _do($content) {
		return preg_replace('/([^a-zA-Z0-9-_&])@([0-9a-zA-Z_]+)/', "$1<a href=\"http://twitter.com/$2\" target=\"_blank\" rel=\"nofollow\">@$2</a>", $content);
	}
}