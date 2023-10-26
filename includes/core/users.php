<?php

if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if ( ! class_exists('UT_Core_Users') ) {
    class UT_Core_Users {
        static function getWithRoles() {
            global $wpdb;

            $querystr = "
                SELECT 
                  $wpdb->users.ID,
                  $wpdb->users.user_login,
                  $wpdb->users.display_name,
                  $wpdb->usermeta.meta_value as capabilities
                FROM $wpdb->users INNER JOIN $wpdb->usermeta
                ON($wpdb->users.ID = $wpdb->usermeta.user_id) 
                WHERE $wpdb->usermeta.meta_key = 'wp_capabilities'
                ORDER BY $wpdb->users.user_login ASC
             ";

            $users = $wpdb->get_results($querystr, OBJECT);

            foreach ($users as &$user) {
                $user->capabilities = unserialize($user->capabilities);
            }

            return $users;
        }
    }
}