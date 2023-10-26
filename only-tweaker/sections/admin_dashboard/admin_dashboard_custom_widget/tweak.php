<?php

class OT_admin_dashboard_custom_widget_Tweak {
	function settings() {
		$f = array();

		$f[] = OT_Helper::switcher( 'admin_dashboard_custom_widget', array(
			'right_title' => __( 'Widget visible', OT_SLUG )
		) );
		$f[] = OT_Helper::field( '_admin_dashboard_custom_widget_title', 'text', array(
			'right_title' => __( 'Title:', OT_SLUG ),
			'default'     => 'Ultimate Tweaker Custom Widget'
		) );
		$f[] = OT_Helper::field( '_admin_dashboard_custom_widget_text', 'textarea', array(
			'right_title' => __( 'Text:', OT_SLUG )
		) );

		return $f;
	}

	function tweak() {
		add_action( 'wp_dashboard_setup', array( $this, '_do' ), 10000 );
	}

	function _do() {
		global $wp_meta_boxes;
		$id = 'ultimate_tweaker_custom_widget' .  OT_Helper::id();

		wp_add_dashboard_widget(
			$id,
			$this->options->_admin_dashboard_custom_widget_title ?  $this->options->_admin_dashboard_custom_widget_title : 'Ultimate Tweaker Custom Widget',
			array( $this, '_echo' )
		);

		$dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

		$my_widget = array( $id => $dashboard[ $id ] );
		unset( $dashboard[ $id ] );

		$sorted_dashboard                             = array_merge( $my_widget, $dashboard );
		$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
	}

	function _echo() {
		echo do_shortcode( $this->options->_admin_dashboard_custom_widget_text );
	}
}