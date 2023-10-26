<?php

class OT_admin_branding_favicon_Tweak {
	function settings( ) {
		return OT_Helper::field( 'admin_branding_favicon', 'media', array(
			'url'      => true,
			'title'    => __( 'Favicon', OT_SLUG ),
			'desc'    => __( 'Add favicon for admin area.', OT_SLUG ),
		) );
	}

	function tweak() {
		add_action('admin_head', array($this, '_do'));
	}

	function _do() {
		?><link rel="shortcut icon" href="<?php echo $this->value['url']; ?>" /><?php
	}
}