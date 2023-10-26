<?php
/**
 * Created by Amino-Studio.
 * Url: http://amino-studio.com/
 * License: http://amino-studio.com/license/
 */

if (!class_exists('OT_Starter_Settings')) {
    class OT_Starter_Settings {
        /**
         * @param $helper OT_Core_Helper
         */
        static function start($helper) {
            new OT_Starter_Settings($helper);

            $helper->requireOnce('role_manager/role_manager.php');
            $rm = new OT_Role_Manager($helper, 'manage_options');
        }

        public $capability = 'manage_options';

        /** @var  OT_Core_Helper */
        private $helper;

        /**
         * @return OT_Core_Helper
         */
        public function getHelper() {
            return $this->helper;
        }

        public function __construct($helper) {
            $this->helper = $helper;

            $menu = $this->helper->createMenu();
            $menu->setTitle($this->helper->getName());
            $menu->setName($this->helper->getName());
            $menu->setSlug($this->helper->getSlug());

            $menu->setOn(array(
                'load'       => array($this, 'onSettingLoad'),
                'output'     => array($this, 'onSettingOutput'),
                'outputData' => array($this, 'onSettingOutputData'),
            ));
            $helper->addJsonAction(array(
                'save'         => array($this, 'save'),
                'save-network' => array($this, 'saveNetwork'),
            ), $this->capability);
        }

        function save($data) {
            update_option($this->helper->slug, $data);

            return $data;
        }


        function saveNetwork($data) {
            if(!current_user_can('manage_network_options')) {
                wp_send_json_error('No access', 403);
            }
            update_option($this->helper->slug . '-network', $data);

            return $data;
        }

        function onSettingLoad($helper, $adminMenu) {
            wp_enqueue_media();
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('underscore');
            wp_enqueue_script('backbone');
            wp_enqueue_style('wp-color-picker');

            if (!$helper->isDev) {
                $helper->style('build/app/bundle');
            }
        }

        function onSettingOutput($helper, $adminMenu) {
            $helper->styles(array('assets/select2/select2'));
            $helper->script(array('assets/select2/select2'));


            $helper->script(
                sprintf($this->getHelper()->applyFilters("get_app_js", '%s'), 'build/app/bundle'),
                null,
                array(
                    'handle' => $this->helper->getSlug(),
                    'deps'   => array(
                        'jquery',
                        'underscore',
                        'backbone',
                        'wp-color-picker'
                    )
                )
            );


            $helper->script('role_manager/script');

            echo '<div id="wpas_panel"></div>';
        }

        public function onSettingOutputData() {
            $data = $this->getHelper()->applyFilters("setting_data", array());

            return $data;
        }
    }
}