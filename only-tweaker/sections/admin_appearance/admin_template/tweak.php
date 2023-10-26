<?php

class OT_admin_template_Tweak {
	function settings() {
		$f = array();

		$f[] = OT_Helper::field( 'admin_template', 'radio',  array(
			'title' => __( 'Template', OT_SLUG ),
			'options' => array(
				'' => 'Default',
				'flat' => 'Flat (no shadows)',
//				'transparent' => 'Transparent',
			)
		) );

		return $f;
	}

	function tweak() {
		add_action('admin_body_class', array($this, 'bodyClass'));
		add_action( 'admin_print_styles', array($this, 'style') );
	}

	function bodyClass( $classes ) {
		$classes .= " ut-admin-template-{$this->value} ";
		return $classes;
	}
	function style() {
		wp_enqueue_style('admin_appearance');
	}
}