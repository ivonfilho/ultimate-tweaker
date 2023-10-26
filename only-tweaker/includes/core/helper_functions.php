<?php

if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if ( ! function_exists( 'am_e' ) ) {
    function am_e() {
        do_action('am_e', func_get_args());
//        if(defined('AM_DEV') && AM_DEV) {
//            echo '<script>console.log.call(this, '.(wp_json_encode(func_get_args())).')</script>';
//        }
    }
}
if ( ! function_exists( 'am_d' ) ) {
    function am_d() {
        do_action('am_d', func_get_args());
/*        if(defined('AM_DEV') && AM_DEV) {
            foreach (func_get_args() as $data) {
                highlight_string("<?php\n\$data =\n" . var_export($data, true) . ";\n?>");
            }
            die();
        }*/
    }
}