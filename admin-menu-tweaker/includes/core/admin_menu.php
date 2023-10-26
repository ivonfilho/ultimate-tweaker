<?php

if (!defined('ABSPATH')) {
    die('-1');
}

if (!class_exists('AMT_Core_AdminMenu')) {
    class AMT_Core_AdminMenu {
        /** @var  AMT_Core_Helper */
        private $helper;

        private $hookName;

        private $title = '';
        private $name = '';
        private $capability = 'manage_options';
        private $icon = 'assets/admin-icon.png';
        private $slug = null;
        private $onLoad = null;
        private $onOutput = null;
        private $onOutputData = null;

        /**
         * @return null
         */
        public function getOnOutputData() {
            return $this->onOutputData;
        }

        /**
         * @return null
         */
        public function getOnLoad() {
            return $this->onLoad;
        }

        /**
         * @return null
         */
        public function getOnOutput() {
            return $this->onOutput;
        }

        /**
         * @return null
         */
        public function getSlug() {
            return $this->slug ? $this->slug : $this->helper->slug;
        }

        /**
         * @param null $slug
         */
        public function setSlug($slug) {
            $this->slug = $slug;
        }

        /**
         * @return string
         */
        public function getTitle() {
            return $this->title;
        }

        /**
         * @param string $title
         */
        public function setTitle($title) {
            $this->title = $title;
        }

        /**
         * @return string
         */
        public function getName() {
            return $this->name;
        }

        /**
         * @param string $name
         */
        public function setName($name) {
            $this->name = $name;
        }

        /**
         * @return string
         */
        public function getCapability() {
            return $this->capability;
        }

        /**
         * @param string $capability
         */
        public function setCapability($capability) {
            $this->capability = $capability;
        }

        /**
         * @return string
         */
        public function getIcon() {
            return $this->icon;
        }

        public function setOn($event, $cb = null) {
            if (is_array($event)) {
                foreach ($event as $k => $v) {
                    $this->setOn($k, $v);
                }

                return;
            }
            if ($cb === null) {
                throw new Exception('cb is null');
            }
            switch ($event) {
                case 'load':
                    $this->onLoad = $cb;
                    break;
                case 'output':
                    $this->onOutput = $cb;
                    break;
                case 'outputData':
                    $this->onOutputData = $cb;
                    break;
                default:
                    throw new Exception('Not valid event');
                    break;
            }
        }

        /**
         * @param string $icon
         */
        public function setIcon($icon) {
            $this->icon = $icon;
        }

        public function __construct($helper, $addMenuPriority = 100) {
            $this->helper = $helper;

            if (!is_admin()) return;

//            if ($this->helper->isDev && did_action('admin_menu')) {
//                throw new Exception('Action already fired.');
//            }

            add_action('admin_menu', array($this, 'menu'), $addMenuPriority);
            //add_action('network_admin_menu', array($this, 'menu'), $addMenuPriority);
        }

        public function menu() {
            if ($this->helper->getUpdater()->isUltimateTweakerActive()) {
                $this->hookName = add_submenu_page(
                    'only-tweaker',
                    $this->name,
                    $this->title,
                    $this->getCapability(),
                    $this->getSlug(),
                    array($this, 'output'));
            } else {
                $this->hookName = add_menu_page(
                    $this->name,
                    $this->title,
                    $this->getCapability(),
                    $this->getSlug(),
                    array($this, 'output'),
                    $this->helper->pluginUrl($this->icon),
                    null);
            }

            add_action("load-{$this->hookName}", array($this, 'load'));
        }

        public function load() {
            add_filter('admin_body_class', array($this, 'addBodyClass'));
            remove_all_filters('admin_footer_text');
            add_filter('admin_footer_text', array($this, 'adminFooterText'));

            if ($this->onLoad) {
                call_user_func_array($this->onLoad, array(
                    $this->helper,
                    $this
                ));
            }
        }

        public function getData() {
            global $wp_version;
            $data = array(
                'debug'            => WP_DEBUG,
                'wpVersion'        => $wp_version,
                'version'          => $this->helper->version,
                'textDomain'       => $this->helper->slug,
                'siteUrl'          => get_site_url(),
                'serverSoftware'   => isset($_SERVER["SERVER_SOFTWARE"]) ? $_SERVER["SERVER_SOFTWARE"] : null,
                'phpVersion'       => phpversion(),
                'slug'             => $this->getSlug(),
                'locale'           => get_locale(),
                'is_network_admin' => is_network_admin(),
                'nonce'            => $this->helper->createNonce(),
                'pluginUrl'        => $this->helper->pluginUrl('/')
            );

            if ($this->onOutputData) {
                $result = call_user_func_array($this->onOutputData, array(
                    $this->helper,
                    $this,
                    &$data
                ));
                if (is_array($result)) {
                    $data = array_merge($data, $result);
                }
            }

            return $data;
        }

        public function output() {
            global $wp_scripts;

            if ($this->onOutput) {
                ?>
                <noscript>
                    <div class="no-js"><?php echo __('Warning: This options panel will not work properly without JavaScript, please enable it.', $this->helper->getSlug()); ?></div>
                </noscript>
                <?php
                call_user_func_array($this->onOutput, array(
                    $this->helper,
                    $this
                ));
            }

            $scripts = array_values($wp_scripts->queue);
            $this->helper->localizeScript($this->helper->getSlug(), 'APP', $this->getData());
        }

        public function addBodyClass($classes) {
            return $classes . ' wpas wpas-' . $this->helper->slug . ' ';
        }

        public function adminFooterText() {
            return '<div style="float: left;">Desenvolvimento' . '<div style="display: inline-block;margin: 0px 7px;color:#e74c3c;">♥</div>' .
                ' <a href="https://hostpress.com.br" target="_blank">HostPress</a></div>';
        }

        public function addJsonAction($name, $cb = null) {
            if (is_array($name)) {
                foreach ($name as $k => $n) {
                    $this->addJsonAction($k, $n);
                }

                return;
            }
            if ($cb === null) {
                throw new Exception('cb is null');
            }

            $me = $this;
            add_action('wp_ajax_' . $this->getSlug() . '-' . $name, function () use ($me, $cb) {
                if (!$me->helper->isValidNonce($_POST['_wpnonce']) || !current_user_can($me->getCapability())) {
                    wp_send_json_error('No access', 403);
                }

                $result = call_user_func_array($cb, array(
                    $me->helper,
                    $me
                ));

                wp_send_json($result);
            });
        }
    }
}