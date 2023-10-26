<?php

class OT_media_post_no_img_a_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'media_post_no_img_a', array(
			'title'   => __( 'Remove links around images', OT_SLUG ),
			'desc'   => htmlspecialchars(__( 'WordPress automatically wrap images tags in <a></a> with link to full image.', OT_SLUG )),
		) );
	}

	function tweak() {
		add_filter( 'the_content', array($this, '_do') );
	}

	function _do($content) {
		$content =
			preg_replace(
				array('{<a(.*?)(wp-att|wp-content/uploads)[^>]*><img}',
					'{ wp-image-[0-9]*" /></a>}'),
				array('<img','" />'),
				$content
			);
		return $content;
	}
}