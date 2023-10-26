<?php
if (!class_exists('AMT_Icons_Icons')) {
    class AMT_Icons_Icons {
        static $fonts = array(
            'dashicons', 'mdi', 'fontawesome', 'foundation', 'ionicons'
        );
        static $names = array(
            'dashicons'   => 'Dashicons',
            'mdi'         => 'Material Design Icons',
            'fontawesome' => 'Font Awesome',
            'foundation'  => 'Foundation',
            'ionicons'    => 'Ionicons'
        );

        static function get($font) {
            require_once($font . '.php');

            return call_user_func('getIcons_' . $font);
        }

        static function getAll() {
            $data = array();
            foreach (self::$fonts as $font) {
                $data[ $font ] = array(
                    'name'  => self::$names[ $font ],
                    'icons' => self::get($font),
                );
            }

            return $data;
        }
    }
}