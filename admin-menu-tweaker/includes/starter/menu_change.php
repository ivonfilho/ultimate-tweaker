<?php

if (!class_exists('AMT_Starter_MenuChange')) {
    class AMT_Starter_MenuChange {
        /**
         * @param $helper AMT_Core_Helper
         * @param $options array
         */
        static function start($helper, $options) {
            $instance = new AMT_Starter_MenuChange($helper, $options);

            add_filter('admin_menu', array($instance, 'changeSlugs'), 99999);

            add_filter('parent_file', array($instance, 'transformMenuBeforeOutput_step1'), 99999);
            add_action('adminmenu', array($instance, 'restoreMenuAfterOutput_step2'), 0);

            add_filter('submenu_file', function($submenu_file, $parent_file) {
                return apply_filters( 'at_submenu_file', $submenu_file, $parent_file );
            }, 99999, 2);
        }

        /** @var  AMT_Core_Helper */
        public $helper;

        /**
         * @return AMT_Core_Helper
         */
        public function getHelper() {
            return $this->helper;
        }

        /** @var  array */
        private $options;

        private $menuCopy;

        /**
         * AMT_MenuFirewall constructor.
         * @param $helper AMT_Core_Helper
         * @param $options array
         */
        public function __construct($helper, $options) {
            $this->helper = $helper;
            $this->options = $options;
        }

        public function transformMenuBeforeOutput_step1($parent_file) {
            global $menu, $submenu;

            //am_d($menu);am_d($submenu);

            $this->menuCopy = array( $menu, $submenu );

            $this->helper->requireOnce('includes/menu/controller.php');

            $menuController = new AMT_Menu_Controller($this->helper, $menu, $submenu);
            $menuController->doChanges($this->options);

            $me = $this;
            $this->getHelper()->addFilter("setting_data", function($data) use($me, $menuController) {
                $me->helper->requireOnce('includes/core/users.php');
                $me->helper->requireOnce('includes/core/roles.php');

                $menus = $menuController->getOriginalArray();
                $data['menu'] = $menus;
                $data['users'] = AMT_Core_Users::getWithRoles();

                $additionalCapabilities = array();
                foreach ($menus as $menu) {
                    if ($menu['capability']) $additionalCapabilities[ $menu['capability'] ] = true;
                    if ($menu['notCapability']) $additionalCapabilities[ $menu['notCapability'] ] = true;
                }
                $data['roles'] = AMT_Core_Roles::getWithCapabilities($additionalCapabilities);

                return $data;
            });

            $parent_file = apply_filters( 'at_parent_file', $parent_file );

            return $parent_file;
        }

        public function restoreMenuAfterOutput_step2() {
            global $menu, $submenu;
            list( $menu, $submenu ) = $this->menuCopy;
            do_action('at_after_output');
        }

        public function changeSlugs() {
            global $menu, $submenu;

//            if(!is_array($this->options)) return;
//            foreach ($this->options as $id => $changes ) {
//                if(isset($changes['slug'])) {
//                    if(isset($menu[]))
//                }
//            }
        }
    }
}