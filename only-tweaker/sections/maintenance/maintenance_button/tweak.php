<?php

class OT_maintenance_button_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'maintenance_button', array(
			'title'    => __( 'Button in toolbar', OT_SLUG ),
		) );
	}

	function tweak() {
		OT_Helper::$_->style('style', __FILE__);
		add_action( 'init', array( $this, 'init' ), 0 );
		add_action( 'admin_bar_menu', array( &$this, 'admin_bar_menu' ), 8);
	}

	function init() {
		if ( isset( $_GET['ut_maintenance'] ) ) {
            $is_enabled = $_GET['ut_maintenance'] == 1 ? 1 : 0;
            $settings       = get_option( OT_SLUG );
            if(!is_array($settings)) $settings = array();
            if(!is_array($settings[''])) $settings[''] = array();
            $settings['']['maintenance'] = $is_enabled ? '1' : '';
            update_option( OT_SLUG, $settings );
            wp_redirect(remove_query_arg('ut_maintenance', $_SERVER['REQUEST_URI']));
            exit;
		}
	}

	function admin_bar_menu($wp_admin_bar) {
		$id = 'ut_maintenance';
		$checkedIcon = '<span class="ut_maintetance_icon dashicons-before dashicons-yes"></span>';
		$uncheckedIcon = '<span class="ut_maintetance_icon"></span>';
		$title = '<img style=" vertical-align: middle;" src="'.plugins_url("assets/under3.svg", OT_Helper::$_->__FILE__) . '">';//<span class="ab-label">' . __( 'Enabled', OT_SLUG ) . '</span>

        $settings       = get_option( OT_SLUG );
        $is_enabled = false;
        if(!is_array($settings)) $settings = array();
        if(!is_array($settings[''])) $settings[''] = array();
        if(isset($settings['']) && isset($settings['']['maintenance']) && $settings['']['maintenance']) {
            $is_enabled = 1;
        }

			$wp_admin_bar->add_menu( array(
			'id'        => $id,
			'parent'    => 'top-secondary',
			'title'     => $title,
			'href'      => '',
			'meta'      => array(
				'class'     => ($is_enabled?'maintenance-enabled':''),
				'title'     => __('Maintenance Mode', OT_SLUG),
			),
		) );

		$wp_admin_bar->add_menu( array(
			'id'        => $id . '-enabled',
			'parent'    => $id,
			'title'     => ($is_enabled?$checkedIcon:$uncheckedIcon) . __('Enabled', OT_SLUG),
			'href'      => add_query_arg( array( 'ut_maintenance' => 1 ) )
		) );

		$wp_admin_bar->add_menu( array(
			'id'        => $id . '-disabled',
			'parent'    => $id,
			'title'     => (!$is_enabled?$checkedIcon:$uncheckedIcon) . __('Disabled', OT_SLUG),
			'href'      => add_query_arg( array( 'ut_maintenance' => 0 ) )
		) );
	}
}