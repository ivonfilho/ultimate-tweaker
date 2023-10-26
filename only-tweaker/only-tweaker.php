<?php


if (!defined('ABSPATH')) {
    die('-1');
}


define('OT_NAME', 'Configurações');
define('OT_VERSION', '2.3.1');
define('OT_SLUG', 'only-tweaker');

if (!class_exists('only_tweaker_Plugin_File')) {
    class only_tweaker_Plugin_File {
        /** @var OT_Core_Helper  */
        public $helper;

        /**
         * @return OT_Core_Helper
         */
        public function getHelper() {
            return $this->helper;
        }

        /**
         * @return OT_Settings
         */
        public function getSettings() {
            return $this->settings;
        }

        private $settings;

        function __construct() {
            require_once(plugin_dir_path(__FILE__) . 'includes/core/helper.php');

            $this->helper = new OT_Core_Helper(__FILE__,
                OT_NAME,
                OT_SLUG,
                OT_VERSION,
                (defined('WP_DEBUG') && WP_DEBUG == true));

            if(!$this->helper->isSupported()) return;

            $this->helper->requireOnce('includes/only/helper.php');
            OT_Helper::$_ = $this->helper;

//            
//            

//			register_activation_hook( __FILE__, array ( $this, 'onActivate') );
//			register_deactivation_hook( __FILE__, array ( $this, 'onDeactivate') );

            if (is_admin()) {
//                if($this->helper->isPluginActive('ultimate-tweaker')) {
//                    $this->helper->deactivatePlugin( 'ultimate-tweaker' );
//                }

                $this->helper->requireOnce('includes/starter/init_tweaks.php');
                OT_Starter_InitTweaks::start($this->helper);

                $this->helper->requireOnce('includes/starter/settings.php');
                OT_Starter_Settings::start($this->helper);

                $this->helper->requireOnce('includes/starter/settings_load_tweaks.php');
                OT_Starter_SettingsLoadTweaks::start($this->helper);

                $this->helper->requireOnce('includes/starter/settings_load_roles.php');
                OT_Starter_SettingsLoadRoles::start($this->helper);

                $this->helper->script('assets/vendor/mousetrap-with-global.custom', null, array('handle' => 'mousetrap'));
                $this->helper->style('assets/admin_appearance/style');
            }

//
            $me = $this;
            add_action('plugins_loaded', function() use($me) {
                $me->helper->requireOnce('includes/starter/apply_tweaks.php');

                if(is_multisite()) {
                    $options = $this->getHelper()->getOptions($this->getHelper()->slug . '-network')->getData();
                    $values = $this->inheritOptions($options);
                    OT_Starter_ApplyTweaks::start($me->helper, $values);
                }

                $options = $this->getHelper()->getOptions($this->getHelper()->slug)->getData();
                $values = $this->inheritOptions($options);

                OT_Starter_ApplyTweaks::start($me->helper, $values);

                $this->prepareOptions();
            }, 0);
        }

        function inheritOptions($options) {
            if(!is_array($options)) return array();
            $globalOptions = isset($options['']) ? $options[''] : array();

            $userOptions = array();

            if(is_user_logged_in()) {
                $role = $this->helper->getCurrentUserRole();
                $roleOptions = isset($options["role:$role"]) ? $options["role:$role"] : array();

                $user = wp_get_current_user()->user_login;
                $userOptions = isset($options["user:$user"]) ? $options["user:$user"] : array();
            } else {
                $roleOptions = isset($options["guest"]) ? $options["guest"] : array();
            }

            foreach ($roleOptions as $k=>$v) {
                if(!$v) {
                    unset($roleOptions[$k]);
                }
            }
            foreach ($userOptions as $k=>$v) {
                if(!$v) {
                    unset($userOptions[$k]);
                }
            }

            return array_replace_recursive($globalOptions, $roleOptions, $userOptions);
        }

        function prepareOptions() {
            $options = $this->getHelper()->getOptions(
                $this->getHelper()->slug . (is_network_admin() ? '-network' : '')
            )->getData();

            $this->getHelper()->addFilter("setting_data", function($data) use($options) {
                $data['values'] = $options;
                return $data;
            });
        }

        function onActivate() {
            flush_rewrite_rules();
        }

        function onDeactivate() {
            flush_rewrite_rules();
        }
    }
}

new only_tweaker_Plugin_File();
