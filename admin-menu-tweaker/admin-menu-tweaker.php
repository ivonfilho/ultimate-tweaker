<?php


if (!defined('ABSPATH')) {
    die('-1');
}


define('AMT_NAME', 'Admin Menu');
define('AMT_VERSION', '1.2.8');
define('AMT_SLUG', 'admin-menu-tweaker');

if (!class_exists('admin_menu_tweaker_Plugin_File')) {
    class admin_menu_tweaker_Plugin_File {
        /** @var AMT_Core_Helper  */
        public $helper;

        /**
         * @return AMT_Core_Helper
         */
        public function getHelper() {
            return $this->helper;
        }

        /**
         * @return AMT_Settings
         */
        public function getSettings() {
            return $this->settings;
        }

        private $settings;

        function __construct() {
            require_once(plugin_dir_path(__FILE__) . 'includes/core/helper.php');

            $this->helper = new AMT_Core_Helper(__FILE__,
                AMT_NAME,
                AMT_SLUG,
                AMT_VERSION,
                (defined('WP_DEBUG') && WP_DEBUG == true));

            if(!$this->helper->isSupported()) return;

            $this->helper->requireOnce('includes/starter/locale.php');
            AMT_Starter_Locale::start($this->helper);

            
            

            if (is_admin()) {
                $this->helper->requireOnce('includes/starter/settings.php');
                AMT_Starter_Settings::start($this->helper);
            }

            $me = $this;
            add_action('admin_menu', function() use($me) {
                list($changes, $custom) = $me->prepareOptions();

                $me->helper->requireOnce('includes/starter/menu_creator.php');
                AMT_Starter_MenuCreator::start($me->helper, $custom, $changes);

                $me->helper->requireOnce('includes/starter/menu_firewall.php');
                AMT_Starter_MenuFirewall::start($me->helper, $changes);

                $me->helper->requireOnce('includes/starter/menu_change.php');
                AMT_Starter_MenuChange::start($me->helper, $changes);
            });
        }

        function inheritOptions($options) {
            if(!is_array($options)) return array();
            $globalOptions = isset($options['']) ? $options[''] : array();
//var_dump($globalOptions);die();
            $role = $this->helper->getCurrentUserRole();
            $roleOptions = isset($options["role:$role"]) ? $options["role:$role"] : array();

            $user = wp_get_current_user()->user_login;
            $userOptions = isset($options["user:$user"]) ? $options["user:$user"] : array();

            return array_replace_recursive($globalOptions, $roleOptions, $userOptions);
        }

        function prepareOptions() {
            $options = $this->getHelper()->getOptions()->getData();

            $this->getHelper()->addFilter("setting_data", function($data) use($options) {
                $data['values'] = $options;
                return $data;
            });

            global $actions_log;
//            am_d($actions_log);

            return array(
                isset($options['changes']) ? $this->inheritOptions($options['changes']) : array(),
                isset($options['custom']) ? $this->inheritOptions($options['custom']) : array(),
            );
        }
    }
}

$amt = new admin_menu_tweaker_Plugin_File();
