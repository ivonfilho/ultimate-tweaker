<?php

if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if ( ! class_exists('AMT_Core_Users') ) {
    class AMT_Core_Users {
        static function getWithRoles() {
            global $wpdb;

            try {
                $querystr = "
                SELECT 
                  $wpdb->users.ID,
                  $wpdb->users.user_login,
                  $wpdb->users.display_name,
                  $wpdb->usermeta.meta_value as capabilities
                FROM $wpdb->users INNER JOIN $wpdb->usermeta
                ON($wpdb->users.ID = $wpdb->usermeta.user_id) 
                WHERE $wpdb->usermeta.meta_key = '".$wpdb->prefix."capabilities'
                ORDER BY $wpdb->users.user_login ASC
             ";

                $users = $wpdb->get_results($querystr, OBJECT);
            } catch (Exception $e) {}
            foreach ($users as &$user) {
                $user->capabilities = unserialize($user->capabilities);
            }

            return $users;
        }
    }
}