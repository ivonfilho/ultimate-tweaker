<?php

if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if ( ! class_exists('AMT_Core_Roles') ) {
    class AMT_Core_Roles {
        static function getNames() {
            global $wp_roles;

            if (!isset($wp_roles))
                $wp_roles = new WP_Roles();

            return $wp_roles->get_names();
        }

        /**
         * @param null $additionalCaps Some plugins use
         * @return array
         */
        static function getWithCapabilities($additionalCaps = null) {
            global $wp_roles;

            if (!isset($wp_roles))
                $wp_roles = new WP_Roles();

            $roles = $wp_roles->roles;

            if($additionalCaps && is_array($additionalCaps)) {
                foreach ($roles as $roleId=>&$role) {
                    $user = new WP_User((object) array('ID'=> 0));
                    $user->caps = array($roleId=>true);
                    $user->get_role_caps();
                    //$user->allcaps = array('administrator'=>true);

                    foreach ($additionalCaps as $additionalCap=>$active) {
                        if($user->has_cap($additionalCap)) {
                            $role['capabilities'][$additionalCap] = true;
                        }
                    }
                }
            }

            return $roles;
        }
    }
}