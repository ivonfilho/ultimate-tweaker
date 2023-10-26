<?php

class OT_admin_dashboard_one_column_Tweak {
	function settings( ) {
		return OT_Helper::switcher( 'admin_dashboard_one_column', array(
			'title'    => __( 'Dashboard 1 column layout', OT_SLUG ),
			'on_desc'    => __( 'Force 1 column layout.', OT_SLUG ),
			'off_desc'    => __( 'Depends user setting: 1 or 2.', OT_SLUG ),
		) );
	}

    function admin_head() {
        ?>
        <style>
            #dashboard-widgets .postbox-container {
                width: 100% !important;
            }
        </style>
        <?php
    }

	function tweak() {
		add_filter('screen_layoOT_columns', array($this, '_do'));
        add_action('admin_head', array($this, 'admin_head'));
		add_filter('get_user_option_screen_layoOT_dashboard', create_function('', 'return 1;'));
	}

	function _do($columns) {
		$columns['dashboard'] = 1;


		return $columns;
	}
}