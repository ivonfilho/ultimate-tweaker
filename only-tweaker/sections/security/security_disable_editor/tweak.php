<?php

class OT_security_disable_editor_Tweak {
	function settings() {
		return OT_Helper::switcher( 'security_disable_editor', array(
			'title' => __( 'Disable file editing', OT_SLUG )
		) );
	}

	function tweak() {
		OT_Helper::blockUserCap('edit_files');
		OT_Helper::blockUserCap('edit_plugins');
		OT_Helper::blockUserCap('edit_themes');
	}
}