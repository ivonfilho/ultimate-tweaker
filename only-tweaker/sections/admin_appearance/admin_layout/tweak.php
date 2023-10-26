<?php

class OT_admin_layout_Tweak {
	function settings() {
		$f = array();

		$f[] = OT_Helper::field( 'admin_layout', 'radio',  array(
			'title' => __( 'Menu Layout', OT_SLUG ),
			'options' => array(
				'' => 'Default',
				'full_height' => 'Full-Height'
			)
		) );

		return $f;
	}

	function tweak() {
		add_action('admin_body_class', array($this, 'bodyClass'));
		add_action( 'admin_print_styles', array($this, 'style') );
	}

	function style() {
		wp_enqueue_style('admin_appearance');
	}

	function bodyClass( $classes ) {
		$classes .= " ut-admin-layout-{$this->value} ";
		return $classes;
	}
}