<?php

if (!class_exists('AMT_Starter_MenuFirewall')) {
    class AMT_Starter_MenuFirewall {
        /**
         * @param $helper AMT_Core_Helper
         * @param $options array
         */
        static function start($helper, $options) {
            $instance = new AMT_Starter_MenuFirewall($helper, $options);
            add_action( "current_screen", array( $instance, 'checkPage' ));
        }

        /** @var  AMT_Core_Helper */
        private $helper;

        /** @var  array */
        private $options;

        /**
         * AMT_Starter_MenuFirewall constructor.
         * @param $helper AMT_Core_Helper
         * @param $options array
         */
        public function __construct($helper, $options) {
            $this->helper = $helper;
            $this->options = $options;
        }

        private $safeBlockRuleSlugs = array( 'ultimate-tweaker', 'admin-menu-tweaker' );

        public function checkPage() {
            if(!is_array($this->options)) return;

            //global $menu, $submenu;

//            add_action('in_admin_header', function() {
//                add_action('admin_notices', function() {
//                    echo '<div class="updated"><p>sdsdsd</p></div>';
//                }, 0);
//            });
//            add_action('in_admin_footer', function() {
//                echo 'dddddddddddddd';
//            });

            foreach ($this->options as $id => $option) {
                if(!isset($option['isHidden'])) continue;
                $block = $option['isHidden'] == 1;

                global $plugin_page;
                if( in_array($plugin_page, $this->safeBlockRuleSlugs) ) {
                    $block = $option['isHidden'] == 10;
                }

                if(!$block) continue;

                if($this->currentPageIs($id)) {
                    $this->doBlock();
                }
            }
        }

        private function currentPageIs($id) {
            global $current_screen;

            $parts = preg_split('/;/', $id);
            $parent = $parts[0];
            $child = isset($parts[1]) ? $parts[1] : null;

            $child = remove_query_arg('autofocus', $child);

//            am_d($id, $parent, $child, $current_screen);

            $slug = null;

            /** FIX: To deny WooCommerce(Orders) if main menu is denied */
            if($parent == 'woocommerce' && !$child) {
                $parent = 'edit.php?post_type=shop_order';
            }

            if($current_screen->base == 'edit') {
                $slug = 'edit.php';
                if($current_screen->post_type != 'post') {
                    $slug = add_query_arg('post_type', $current_screen->post_type, $slug);
                }
            } else if($current_screen->base == 'post') {
                if($current_screen->action == 'add') {
                    $slug = 'post-new.php';
                    if($current_screen->post_type != 'post') {
                        $slug = add_query_arg('post_type', $current_screen->post_type, $slug);
                    }
                }
            } else if($current_screen->base == 'edit-tags') {
                $slug = 'edit-tags.php';
                $slug = add_query_arg('taxonomy', $current_screen->taxonomy, $slug);

                if($current_screen->post_type != 'post') {
                    $slug = add_query_arg('post_type', $current_screen->post_type, $slug);
                }
            } else

            if($current_screen->id == 'dashboard') {
                $slug = 'index.php';
            } else if($current_screen->id == 'update-core') {
                $slug = 'update-core.php';
            } else if($current_screen->id == 'upload') {
                $slug = 'upload.php';
            } else if($current_screen->id == 'media') {
                $slug = 'media-new.php';
            } else if($current_screen->id == 'edit-comments') {
                $slug = 'edit-comments.php';
            } else if($current_screen->id == 'themes') {
                $slug = 'themes.php';
            } else if($current_screen->id == 'customize') {
                $slug = 'customize.php';
            } else if($current_screen->id == 'widgets') {
                $slug = 'widgets.php';
            } else if($current_screen->id == 'nav-menus') {
                $slug = 'nav-menus.php';
            } else if($current_screen->id == 'theme-editor') {
                $slug = 'theme-editor.php';
            } else if($current_screen->id == 'plugins') {
                $slug = 'plugins.php';
            } else if($current_screen->id == 'plugin-install') {
                $slug = 'plugin-install.php';
            } else if($current_screen->id == 'plugin-editor') {
                $slug = 'plugin-editor.php';
            } else if($current_screen->id == 'users') {
                $slug = 'users.php';
            } else if($current_screen->id == 'user' and $current_screen->action == 'add') {
                $slug = 'user-new.php';
            } else if($current_screen->id == 'profile') {
                $slug = 'profile.php';
            } else if($current_screen->id == 'tools') {
                $slug = 'tools.php';
            } else if($current_screen->id == 'import') {
                $slug = 'import.php';
            } else if($current_screen->id == 'export') {
                $slug = 'export.php';
            } else if($current_screen->id == 'options-general') {
                $slug = 'options-general.php';
            } else if($current_screen->id == 'options-writing') {
                $slug = 'options-writing.php';
            } else if($current_screen->id == 'options-reading') {
                $slug = 'options-reading.php';
            } else if($current_screen->id == 'options-discussion') {
                $slug = 'options-discussion.php';
            } else if($current_screen->id == 'options-media') {
                $slug = 'options-media.php';
            } else if($current_screen->id == 'options-permalink') {
                $slug = 'options-permalink.php';
            }

            if($slug == $parent && !$child) {
                return true;
            } else if($slug == $child && $child) {
                return true;
            } else {
                global $plugin_page;
                if(!empty($plugin_page)) {
                    if($plugin_page == $parent && !$child) {
                        return true;
                    } else if($plugin_page == $child && $child) {
                        return true;
                    }
                }
            }



            return false;
        }

        private function doBlock() {
            global $current_screen;

            if($current_screen->id == 'dashboard') {
                global $menu;

                //prepare blocked array my parent
                $blocked = array();
                foreach ($this->options as $id=>$v) {
                    if(isset($v['isHidden']) && $v['isHidden'] == 1) {
                        $parts = preg_split('/;/', $id);
                        $blocked[$parts[0]] = 1;
                    }
                }

                foreach ($menu as $m) {
                    $slug = $m[2];

                    if(isset($blocked[$slug]) || false !== strpos( $m[4], 'wp-menu-separator' )) continue;
                    $u = strpos($m[2], '.php') !== false ? $m[2] : 'admin.php?page=' . $m[2];
                    wp_redirect(admin_url($u));
                    exit;
                }
            }

            wp_die(
                '<h1>' . __( 'Cheatin&#8217; uh?' ) . '</h1>' .
                '<p>' . __( 'Sorry, you are not allowed to access this page.' ) . '</p>',
                403
            );
        }
    }
}