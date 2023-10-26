<?php

class OT_admin_users_hide_admins_Tweak {
	function settings() {
		return OT_Helper::switcher( 'admin_users_hide_admins', array(
			'title' => __( 'Hide administators', OT_SLUG )
		) );
	}

	function tweak() {
        add_action('plugins_loaded', array($this, '_load'));
	}

	function _load() {
		add_action('pre_user_query', array($this, 'pre_user_query'));
		add_action('wp_roles_init', array($this, 'wp_roles_init'));
		add_action('admin_head', array($this, 'admin_head'));
	}

	function admin_head() {
        ?>
        <style>
            .wp-admin ul li.all span.count {display: none;}
        </style>
        <?php
    }

	function wp_roles_init($roles) {
//	    unset($roles->roles['administrator']);
//	    unset($roles->role_objects['administrator']);
	    unset($roles->role_names['administrator']);
    }

	function pre_user_query($user_search) {
		global $wpdb;


        global $wpdb;

        $user_search->query_where =
            str_replace('WHERE 1=1',
                "WHERE 1=1 AND {$wpdb->users}.ID IN (
                 SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
                    WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}user_level' 
                    AND {$wpdb->usermeta}.meta_value = 0)",
                $user_search->query_where
            );
	}
}