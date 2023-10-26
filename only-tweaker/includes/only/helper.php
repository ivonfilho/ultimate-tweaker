<?php
/**
 * Created by Amino-Studio.
 * Url: http://amino-studio.com/
 * License: http://amino-studio.com/license/
 */


if ( ! class_exists( 'OT_stdSettings' ) ) {
    class OT_stdSettings {
        function __construct($data) {
            $this->data = $data;
        }
        function __get($key) {
            return isset($this->data[$key]) ? $this->data[$key] : null;
        }
        function __isset($key) {
            return isset($this->data[$key]);
        }
    }
}

if ( ! class_exists( 'OT_BlockUserCap' ) ) {
    class OT_BlockUserCap {
        function __construct( $cap ) {
            $this->cap = $cap;
        }

        function _cap( $allcaps, $cap, $args ) {
            if ( $this->cap == $args[0] ) {
                $allcaps[ $this->cap ] = false;
            }

            return $allcaps;
        }
    }
}

if ( ! class_exists( 'OT_Helper' ) ) {
	class OT_Helper {

	    static $_;

        static function id( $prefix = '_' ) {
            static $id;

            return $prefix . ++ $id;
        }

        static function blockUserCap( $cap ) {
            $blocker = new OT_BlockUserCap($cap);
            add_filter( 'user_has_cap', array($blocker, '_cap'), 0, 3 );
        }

        static function getRequestRole( $suffix = '' ) {
            return ( isset( $_REQUEST['role'] ) && ! empty( $_REQUEST['role'] ) ) ? $suffix.$_REQUEST['role'] : '';
        }

        static function getUserRole() {
            global $current_user;

            if($current_user) {
                $cu = $current_user;
            } else {
                $cu = wp_get_current_user();
            }

            $user_roles = $cu->roles;
            $user_role = array_shift($user_roles);

            return $user_role;
        }


        static function field($id, $type, $args = null) {
            if(!$args) {
                $args = $type;
                $type = $id;
                $id = rand();
            }
            $args['id'] = $id;
            $args['type'] = $type;
//			!isset($args['default']) && $args['default'] = '';

//			if(isset($args['right_title']) && !empty($args['right_title'])) {
//				$args['title'] = sprintf('<span style="float: right;">%s</span>', $args['right_title']);
//				unset($args['right_title']);
//			}

            if(empty($args['default'])) unset($args['default']);

            return $args;
        }

        static function switcher($key, $data) {
            return self::field( $key, 'switch', array_merge(array(
                'default'  => 0,
                'on'       => __( 'Yes', OT_SLUG ),
                'off'      => __( 'No', OT_SLUG )
            ), $data) );
        }
	}
}