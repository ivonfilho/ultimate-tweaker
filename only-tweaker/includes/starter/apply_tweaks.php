<?php
/**
 * Created by Amino-Studio.
 * Url: http://amino-studio.com/
 * License: http://amino-studio.com/license/
 */

if (!class_exists('OT_Starter_ApplyTweaks')) {
    class OT_Starter_ApplyTweaks {
        /**
         * @param $helper OT_Core_Helper
         */
        static function start($helper, $values) {
            new OT_Starter_ApplyTweaks($helper, $values);
        }

        /** @var  OT_Core_Helper */
        public $helper;

        public function __construct($helper, $values) {
            $this->helper = $helper;

            if( isset($_REQUEST['action']) && strpos($_REQUEST['action'], 'only-tweaker') !== false) return;

            $this->run($values);

            // Force reread fix for is_user_logged_in - because UT loads early
            if(class_exists( 'bbPress' )) {
                global $current_user;
                $current_user = null;
            }
        }

        public function run($settings) {
            $this->helper->requireOnce('includes/only/data.php');

            $is_admin = is_admin();

            $sections       = OT_Only_Data::getSections();

            $settingsStd =  new OT_stdSettings($settings);

            foreach($sections as $section_ID => $tweaks) {
                foreach($tweaks as $tweak_ID => $applyPlace) {
                    if(!is_string($applyPlace)) continue;
                    if(($applyPlace == 'front' && $is_admin)
                        || ($applyPlace == 'admin' && !$is_admin)
                        || !isset($settings[$tweak_ID])
                        || empty($settings[$tweak_ID])
                        || !$settings[$tweak_ID]) continue;



                    // media fix
                    if(is_array($settings[$tweak_ID]) && isset($settings[$tweak_ID]['id']) && isset($settings[$tweak_ID]['url']) && empty($settings[$tweak_ID]['id']) && empty($settings[$tweak_ID]['url'])) continue;
                    // checkboxes fix
//					if(is_array($settings[$tweak_ID]) && !isset($settings[$tweak_ID]['id'])) {
//						$tmparr = array_unique(array_values($settings[$tweak_ID]));
//						if(!in_array(1 ,$tmparr)) continue;
//					}


                    $this->helper->requireOnce("sections/{$section_ID}/{$tweak_ID}/tweak.php" );
                    $tweakCls = "OT_{$tweak_ID}_Tweak";
                    $tweak = new $tweakCls();
                    $tweak->options = &$settingsStd;
                    $tweak->value = &$settings[$tweak_ID];

                    if(method_exists($tweak, 'isAvailable') && !$tweak->isAvailable()) continue;

                    am_e( 'apply', $tweak_ID,  $tweak->value);

                    $tweak->tweak();
                }
            }
        }
    }
}