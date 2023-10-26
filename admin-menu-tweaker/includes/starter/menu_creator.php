<?php

if (!class_exists('AMT_Starter_MenuCreator')) {
    class AMT_Starter_MenuCreator {
        /**
         * @param $helper AMT_Core_Helper
         * @param $custom array
         * @param $changes array
         */
        static function start($helper, $custom, $changes) {
            $instance = new AMT_Starter_MenuCreator($helper, $custom, $changes);

            $instance->createCustomMenus();
//            add_action('admin_menu', array($instance, 'createCustomMenus'), 0);
        }

        /** @var  AMT_Core_Helper */
        private $helper;

        /**
         * @return AMT_Core_Helper
         */
        public function getHelper() {
            return $this->helper;
        }

        /** @var  array */
        private $custom;

        /** @var  array */
        private $changes;

        /**
         * AMT_MenuFirewall constructor.
         * @param $helper AMT_Core_Helper
         * @param $custom array
         */
        public function __construct($helper, $custom, $changes) {
            $this->helper = $helper;
            $this->custom = $custom;
            $this->changes = $changes;
        }

        public function createCustomMenus() {
            if (!is_array($this->custom)) return;

            global $menu;

            foreach ($this->changes as $id=>$changes) {
                if(isset($changes['addCapability']) && $changes['addCapability']) {
                    $capab = $changes['addCapability'];
                    //TODO: I can't find menu item by id(different for author, then just add capability
                    add_filter( 'user_has_cap', function( $allcaps, $cap, $args ) use($capab) {
                        $allcaps[ $capab ] = true;
                        return $allcaps;
                    }, 0, 3 );
//                    foreach ($menu as &$m) {
//                        if ($m[2] == $id) {
//                        }
//                    }
                }
            }

            foreach ($this->custom as $custom) {
                $changes = is_array($this->changes) && isset($this->changes[ $custom['id'] ]) ? $this->changes[ $custom['id'] ] : array();

                if (!$custom['slug']) {
                    $custom['slug'] = $custom['id'];
                }

                if (isset($changes['customSlug']) && $changes['customSlug']) {
                    $custom['slug'] = $changes['customSlug'];
                }

//                if(isset($changes['url'])) {
//                    $isUrlRedirect = filter_var($changes['url'], FILTER_VALIDATE_URL) ? $changes['url'] : false;
//                    if(!$isUrlRedirect) {
//                        $custom['slug'] = $changes['url'];
//                    }
////                    $custom['slug'] = preg_replace("/\\//", "", $changes['url']);
//                    $custom['slug'] = 'redirect-' . substr(base_convert(md5($changes['url']), 16, 10), -7);
//                }
//                am_d(round(0.1*100)*0.01);
//                am_d(round($custom['position'],2));

                if (strpos($custom['iconClass'], 'wp-menu-separator') !== false) {
//                    if($custom['id'] == '2017-01-24_112546')
//                    am_d($custom);
                    $this->add_admin_menu_separator($custom['id'], (isset($changes['position']) ? $changes['position'] : $custom['position']) . '');
                    continue;
                }

                add_menu_page(
                    isset($changes['name']) ? $changes['name'] : $custom['name'],
                    $custom['name'],
                    $custom['capability'],
                    $custom['slug'],
                    function () use ($changes) {
                        // TODO: mover to menu_change if possible
                        if (isset($changes['pageMode']) && $changes['pageMode'] == 'url-redirect') {
                            if (isset($changes['redirectUrl']) && $changes['redirectUrl']) {
                                wp_redirect($changes['redirectUrl']);
                                exit;
                            } else {
                                echo 'Redirect URL is not defined. Open Admin Menu Tweaker and define this URL.';

                            }
                        } else {
                            if (isset($changes['content']) && $changes['content']) {
                                echo do_shortcode($changes['content']);
                            } else {
                                echo 'Content is not defined. Open Admin Menu Tweaker and define this URL.';
                            }

                        }
                    },
                    '',
                    $custom['position'] . '');

//                am_e($menu);
                foreach ($menu as &$m) {
                    if ($m[2] == $custom['slug']) {
                        $m[10] = 'custom';
                        $m[11] = $custom['id'];
                    }
                }
            }

            add_filter('custom_menu_order', '__return_false', 10000);
//            global $menu;
//            am_d($menu);
        }

        function add_admin_menu_separator($id, $position) {
            global $menu;

            if ( isset( $menu[ "$position" ] ) ) {
                $position = $position + substr( base_convert( md5( 'separator' . $position . $id ), 16, 10 ) , -5 ) * 0.00001;
            }

            $menu[ $position . '' ] = array(
                0 => '',
                1 => 'read',
                2 => 'separator' . $position,
                3 => '',
                4 => 'wp-menu-separator',
                10 => 'custom',
                11 => $id
            );
        }
    }
}