<?php

if (!defined('ABSPATH')) {
    die('-1');
}

if (!class_exists('UT_Core_Updater_Loader')) {
    class UT_Core_Updater_Loader {
        /** @var  UT_Core_Helper */
        private $helper;

        function __construct($helper) {
            $this->helper = $helper;

            $me = $this;
            $this->helper->addFilter("setting_data", function ($data) use($me) {
                $data['purchaseCode'] = $me->getPurchaseCode();

                return $data;
            });

            $this->helper->addJsonAction('activate', array($this, 'activate'));
            add_action('admin_init', array($this, 'loaded'));
        }

        private $utSlug = 'ultimate-tweaker';

        public function isUltimateTweakerActive() {
            return is_plugin_active("$this->utSlug/$this->utSlug.php");
        }

        public function getPurchaseCodeOptionName() {
            return ($this->isUltimateTweakerActive() ? $this->utSlug : $this->helper->slug) . '-pc';
        }

        public function getPurchaseCode() {
            return get_option($this->getPurchaseCodeOptionName());
        }

        function loaded() {
            if ( true ) {
                //TODO: remove plugin depends code
                if (!$this->getPurchaseCode()) {
                    add_action('admin_notices', array($this, 'noticeLicenseActivation'));
                } else {
                    require_once( 'plugin_update_check.php' );
                    $MyUpdateChecker = new PluginUpdateChecker_2_0 (
                        'https://kernl.us/api/v1/updates/'.'5879e4433818f675bcaeb772'.'/',
                        $this->helper->__FILE__,
                        $this->helper->slug,
                        1
                    );
                    $MyUpdateChecker->purchaseCode = $this->getPurchaseCode();
//                    require_once('plugin-update-checker.php');
//                    $updateChecker = new PluginUpdateChecker_3_2(
//                        'https://updates.amino-studio.com/' . $this->updaterId . '/',
//                        $this->helper->__FILE__, $this->helper->slug, 1);
//                    $updateChecker->purchaseCode = $this->purchaseCode;
                }
            }
        }

        function activate($data) {
            $purchaseCode = trim(stripslashes($data['purchaseCode']));
            update_option($this->getPurchaseCodeOptionName(), $purchaseCode);
            return array('success' => true);
        }

        public function noticeLicenseActivation() {
            //echo '<div class="updated activation-notice"><p>' .
                //sprintf(__('Please <a href="%s">activate your copy</a> of %s to receive automatic updates.', 'amino'),
                   // admin_url('admin.php?page=' . $this->helper->slug . '#activation'), $this->helper->getName()) . '</p></div>';
        }
    }
}