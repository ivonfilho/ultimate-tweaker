<?php
/**
 * Created by Amino-Studio.
 * Url: http://amino-studio.com/
 * License: http://amino-studio.com/license/
 */

if (!class_exists('OT_Starter_SettingsLoadRoles')) {
    class OT_Starter_SettingsLoadRoles {
        /**
         * @param $helper OT_Core_Helper
         */
        static function start($helper) {
            new OT_Starter_SettingsLoadRoles($helper);
        }

        /** @var  OT_Core_Helper */
        public $helper;

        public function __construct($helper) {
            $this->helper = $helper;

            $helper->addFilter("setting_data", array($this, 'loadData'));
        }

        public function loadData($data) {
            $this->helper->requireOnce('includes/core/users.php');
            $this->helper->requireOnce('includes/core/roles.php');

            $data['users'] = OT_Core_Users::getWithRoles();
            $data['roles'] = OT_Core_Roles::getWithCapabilities();

            return $data;
        }
    }
}