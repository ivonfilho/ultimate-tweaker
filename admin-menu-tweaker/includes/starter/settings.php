<?php
/**
 * Created by Amino-Studio.
 * Url: http://amino-studio.com/
 * License: http://amino-studio.com/license/
 */

if (!class_exists('AMT_Starter_Settings')) {
    class AMT_Starter_Settings {
        /**
         * @param $helper AMT_Core_Helper
         */
        static function start($helper) {
            new AMT_Starter_Settings($helper);
        }

        public $capability = 'manage_options';

        /** @var  AMT_Core_Helper */
        private $helper;

        /**
         * @return AMT_Core_Helper
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
                'save'           => array($this, 'save'),
                'get_icon_fonts' => array($this, 'getIconFonts')
            ), $this->capability);
        }

        public function getIconFonts() {
            $this->helper->requireOnce('includes/icons/icons.php');

            return AMT_Icons_Icons::getAll();
        }

        function save($data) {
            update_option($this->helper->slug, $data);

            return $data;
        }

        function onSettingLoad($helper, $adminMenu) {
            $helper->style('assets/lato/Lato/latofonts');
            $helper->style('assets/lato/LatoLatin/latolatinfonts');

            $helper->style('assets/icons/icon-fonts', null, array('handle' => 'am-icon-fonts'));

            wp_enqueue_media();
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_style('wp-color-picker');

            if (!$helper->isDev) {
                $helper->style('build/app/bundle');
            }
        }

        function onSettingOutput($helper, $adminMenu) {
            $helper->script(array('build/vendor/vendor1', 'build/vendor/vendor2'));

            $helper->script(
                sprintf($this->getHelper()->applyFilters("get_app_js", '%s'), 'build/app/bundle'),
                null,
                array(
                    'handle' => $this->helper->getSlug(),
                    'deps' => array(
                        'jquery',
                        'wp-color-picker'
                    )
                )
            );
        }

        public function onSettingOutputData() {
            $data = $this->getHelper()->applyFilters("setting_data", array());
            return $data;
        }
    }
}