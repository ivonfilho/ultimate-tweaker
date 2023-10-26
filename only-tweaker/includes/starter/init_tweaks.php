<?php
/**
 * Created by Amino-Studio.
 * Url: http://amino-studio.com/
 * License: http://amino-studio.com/license/
 */

if (!class_exists('OT_Starter_InitTweaks')) {
    class OT_Starter_InitTweaks {
        /**
         * @param $helper OT_Core_Helper
         */
        static function start($helper) {
            new OT_Starter_InitTweaks($helper);
        }

        /** @var  OT_Core_Helper */
        public $helper;

        public function __construct($helper) {
            $this->helper = $helper;

            $section_ID = 'admin_metabox';
            $tweak_ID = 'admin_metabox_hide';
            $this->helper->requireOnce("sections/{$section_ID}/{$tweak_ID}/tweak.php");
            $tweakCls = "OT_{$tweak_ID}_Tweak";
            $tweak = new $tweakCls();
            $tweak->initAdditionals();
        }
    }
}