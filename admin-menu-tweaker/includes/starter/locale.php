<?php

if (!class_exists('AMT_Starter_Locale')) {
    class AMT_Starter_Locale {
        /**
         * @param $helper AMT_Core_Helper
         */
        static function start($helper) {
            if (is_admin()) {
//				new AMT_Starter_Locale($helper);
            }
        }

        /** @var  AMT_Core_Helper */
        public $helper;

        /**
         * @return AMT_Core_Helper
         */
        public function getHelper() {
            return $this->helper;
        }

        public function __construct($helper) {
            $this->helper = $helper;

            add_action('init', array($this, 'run'));

        }

        public function run() {
            $domain = $this->helper->getSlug() . '-client';

            $locale = apply_filters('plugin_locale', is_admin() ? get_user_locale() : get_locale(), $domain);
            if ($locale == 'en_US') return true;

            $path = WP_PLUGIN_DIR . '/' . dirname(plugin_basename($this->helper->__FILE__)) . '/locale/';

            $mofile = $domain . '-' . $locale . '.mo';

            if (!load_textdomain($domain, $path . $mofile)) {
                $parts = preg_split('/_/', $locale);
                if (!is_readable($path . $domain . '-' . $parts[0] . '.mo')
                    || !load_textdomain($domain, $path . $domain . '-' . $parts[0] . '.mo')
                ) {
                    return false;
                }
            }

            $this->outStringsForClient();
        }

        public function outStringsForClient() {
            $domain = $this->getHelper()->getSlug() . '-client';
            if (!is_textdomain_loaded($domain)) return;

            $translations = get_translations_for_domain($domain);
            $strings = array();
            foreach ($translations->entries as $id => $entry) {
                $strings[ $id ] = __($id, $domain);
            }

            if (count($strings)) {
                $me = $this;
                $this->getHelper()->addFilter("setting_data", function ($data) use ($me, $strings) {
                    $data['messages'] = $strings;

                    return $data;
                });
            }
        }
    }
}