<?php

class OT_admin_default_editor_Tweak {
	function settings( ) {
		return OT_Helper::field( 'admin_default_editor', 'radio', array(
			'title'    => __( 'Default editor', OT_SLUG ),
			'options'  => array(
				'' => 'Last active',
				'tinymce' => 'Visual',
				'html' => 'Text'
			),
			'desc'    => __( 'Select which editor will be loaded on edit page by default.', OT_SLUG )
		) );
	}

	function tweak() {
		if($this->value == 'html') {
			add_filter( 'wp_default_editor', create_function('', 'return "html";') );
		} elseif($this->value == 'tinymce') {
			add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );
		}
	}
}