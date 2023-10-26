<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'OT_Core_Helper' ) ) {
	class OT_Core_Helper {
        public function id( $prefix = '_' ) {
            static $id;

            return $prefix . ++ $id;
        }

        /** @var OT_Core_Updater_Loader  */
        private $updater;

        /**
         * @return OT_Core_Updater_Loader
         */
        public function getUpdater() {
            return $this->updater;
        }

        public function isPluginActive($name) {
            $a = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
            return
                in_array( "$name/$name.php", $a) || in_array( "$name", $a);
        }

        public function deactivatePlugin($name, $silent = false, $network_wide = null) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');

            $pluginData = get_plugin_data(WP_PLUGIN_DIR . "/$name/$name.php");
            $pluginName = $pluginData['Name'];

            $me = $this;
            add_action('admin_notices', function() use($me, $pluginName) {
                ?>
                <div class="notice notice-error">
                    <p><?php _e( "$pluginName was deactivated by {$me->getName()}. If you want to you this plugin, deactivate {$me->getName()} before.", $me->getSlug() ); ?></p>
                </div>
                <?php
            });

            deactivate_plugins(
                array(
                    "$name/$name.php"
                ),
                $silent, $network_wide
            );
        }

        public function getCurrentUserRole() {
            global $current_user;

            if($current_user) {
                $cu = $current_user;
            } else {
                $cu = wp_get_current_user();
            }

            $user_roles = $cu->roles;
            $user_role = array_shift($user_roles);

            return $user_role;
        }


        public $__FILE__;
		var $isDebug = false;
		var $isDev = false;
		var $pluginPath;
		var $pluginUrl;
		private $name;

        /**
         * @return mixed
         */
        public function getName() {
            return $this->name;
        }
		var $slug;

        /**
         * @return mixed
         */
        public function getSlug() {
            return $this->slug;
        }
		var $version;
		private $_adminUrl = null;

		function __construct( $__FILE__, $name, $slug, $version, $isDebug ) {
            $this->name = $name;
            $this->slug = $slug;
            $this->version = $version;
            $this->__FILE__ = $__FILE__;
            $this->isDebug = $isDebug;
            $this->pluginPath = plugin_dir_path($__FILE__);
            $this->pluginUrl = plugin_dir_url($__FILE__);

//            register_activation_hook( $__FILE__, array( $this, 'activate' ) );

            require_once('helper_functions.php');
            require_once('updater/loader.php');
            $this->updater = new OT_Core_Updater_Loader($this);

            $this->addAction( array( 'wp_enqueue_scripts', 'admin_enqueue_scripts' ),
				array( $this, 'enqueueScriptsOn' ) );
		}

		function isSupported() {
            if (version_compare(PHP_VERSION, '5.3.0', '<')) {
                add_action('admin_notices', array($this, 'unsupportedPhpVersion'));
                return false;
            }

            return true;
        }

		function unsupportedPhpVersion() {
            ?>
            <div class="notice notice-error">
                <p><?php _e( "PHP version ".PHP_VERSION." is unsupported by {$this->getName()} and plugin is not loaded, please upgrade to use this plugin.", $this->getSlug() ); ?></p>
            </div>
            <?php
        }

		function adminUrl() {
		    if(!$this->_adminUrl) $this->_adminUrl = admin_url();
		    return $this->_adminUrl;
        }


        function format() {
            $args = func_get_args();
            if (count($args) == 0) {
                return;
            }
            if (count($args) == 1) {
                return $args[0];
            }
            $str = array_shift($args);
            $str = preg_replace_callback('/\\{(0|[1-9]\\d*)\\}/', create_function('$match', '$args = '.var_export($args, true).'; return isset($args[$match[1]]) ? $args[$match[1]] : $match[0];'), $str);
            return $str;
        }

		public function requireOnce() {
		    foreach (func_get_args() as $arg) {
		        if(is_array($arg)) {
                    foreach ($arg as $arg0) {
                        require_once($this->pluginPath . $arg0);
                    }
                } else {
                    require_once($this->pluginPath . $arg);
                }
            }
        }

		/**
		 * @param     $tag
		 * @param     $function_to_add
		 * @param int $priority
		 * @param int $accepted_args
		 *
		 * @return bool|null
		 *
		 * Alias for array support
		 */
		function addAction( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
			if ( is_array( $tag ) ) {
				$l = null;
				foreach ( $tag as $t ) {
					$l = add_action( $t, $function_to_add, $priority, $accepted_args );
				}

				return $l;
			}

			return add_action( $tag, $function_to_add, $priority, $accepted_args );
		}

		var $_enqueueOn = false;
		var $_enqueueStyleQueue = array();
		var $_enqueueScriptQueue = array();
		var $_enqueueInlineStyleQueue = array();
		var $_enqueueLocalizeScriptQueue = array();

		function enqueueScriptsOn() {
			$this->_enqueueOn = true;

			if ( count( $this->_enqueueStyleQueue ) ) {
				foreach ( $this->_enqueueStyleQueue as $params ) {
					call_user_func_array( array( $this, 'proxyWpEnqueueStyle' ), $params );
				}
				$this->_enqueueStyleQueue = array();
			}

			if ( count( $this->_enqueueInlineStyleQueue ) ) {
				foreach ( $this->_enqueueInlineStyleQueue as $params ) {
					call_user_func_array( array( $this, 'proxyWpAddInlineStyle' ), $params );
				}
				$this->_enqueueInlineStyleQueue = array();
			}
			if ( count( $this->_enqueueScriptQueue ) ) {
				foreach ( $this->_enqueueScriptQueue as $params ) {
					call_user_func_array( array( $this, 'proxyWpEnqueueScript' ), $params );
				}
				$this->_enqueueScriptQueue = array();
			}
			if ( count( $this->_enqueueLocalizeScriptQueue ) ) {
				foreach ( $this->_enqueueLocalizeScriptQueue as $params ) {
					call_user_func_array( array( $this, 'proxyWpLocalizeScript' ), $params );
				}
				$this->_enqueueLocalizeScriptQueue = array();
			}
		}

		function proxyWpEnqueueStyle( $handle, $src = false, $deps = array(), $ver = false, $media = 'all' ) {
			if ( ! $this->_enqueueOn ) {
				$this->_enqueueStyleQueue[] = func_get_args();

				return;
			}

			wp_enqueue_style( $handle, $src, $deps, $ver, $media );
		}

		function proxyWpEnqueueScript( $handle, $src = false, $deps = array(), $ver = false, $in_footer = false ) {
			if ( ! $this->_enqueueOn ) {
				$this->_enqueueScriptQueue[] = func_get_args();

				return;
			}

			wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
		}

		function proxyWpLocalizeScript( $handle, $object_name, $l10n ) {
			if ( ! $this->_enqueueOn ) {
				$this->_enqueueLocalizeScriptQueue[] = func_get_args();

				return;
			}

			wp_localize_script( $handle, $object_name, $l10n );
		}

		function proxyWpAddInlineStyle( $handle, $data ) {
			if ( ! $this->_enqueueOn ) {
				$this->_enqueueInlineStyleQueue[] = func_get_args();

				return;
			}

			$r = wp_add_inline_style( $handle, $data );
		}

		function style( $file, $__FILE__ = null, $options = array() ) {
			if ( $__FILE__ === null ) {
				$__FILE__ = $this->__FILE__;
			}

			$deps     = isset( $options['deps'] ) ? $options['deps'] : array();
			$min      = false;//isset( $options['force_min'] ) ? $options['force_min'] : !$this->isDebug;
			$handle   = isset( $options['handle'] ) ? $options['handle'] : md5($file . $__FILE__);
			$fileName = $file . ( $min ? '.min' : '' ) . '.css';
			//http://stackoverflow.com/questions/959957/php-short-hash-like-url-shortening-websites

			$this->proxyWpEnqueueStyle( $handle,
                substr($fileName, 0, 4) != 'http' ? plugins_url( $fileName, $__FILE__ ) : $file,
                $deps, $this->version );

			return $handle;
		}

		function script( $file, $__FILE__ = null, $options = array() ) {
		    if(is_array($file)) {foreach ($file as $f) $this->script($f, $__FILE__, $options); return null; }

			if ( $__FILE__ === null ) {
				$__FILE__ = $this->__FILE__;
			}

			$deps      = isset( $options['deps'] ) ? $options['deps'] : array();
			$in_footer = isset( $options['in_footer'] ) ? $options['in_footer'] : false;
			$handle    = isset( $options['handle'] ) ? $options['handle'] : md5($file . $__FILE__);
			$min       = false;//isset( $options['force_min'] ) ? $options['force_min'] : !$this->isDebug;

			$fileName = $file . ( $min ? '.min' : '' ) . '.js';

			$this->proxyWpEnqueueScript( $handle,
                substr($fileName, 0, 4) != 'http' ? plugins_url( $fileName, $__FILE__ ) : $file,
//                plugins_url( $fileName, $__FILE__ ),
                $deps, $this->version, $in_footer );

			return $handle;
		}

		function localizeScript( $handle, $object_name, $l10n ) {
			$this->proxyWpLocalizeScript( $handle, $object_name, $l10n );
		}

		private $_inlineStyleCache = array();
		function inlineStyle( $rule, $styles, $handle = 'root' ) {
			$css = sprintf( '%s { %s }', $rule, $styles );

			if(isset($this->_inlineStyleCache[$handle.$css])) return;
			$this->_inlineStyleCache[$handle.$css] = 1;

			if(wp_style_is($handle)) {
                printf( "<style id='%s-inline-css' type='text/css'>\n%s\n</style>\n", esc_attr( $handle.$this->id() ), $css );
            } else {
                $this->proxyWpAddInlineStyle($handle, $css);
            }

//			return $css;
		}

		function styles( $files, $__FILE__ = null, $options = array() ) {
			$hls = array();

			foreach ( $files as $file ) {
				$hls[] = $this->style( $file, $__FILE__, $options );
			}

			return $hls;
		}

		function scripts( $files, $__FILE__ = null, $options = array() ) {
			$hls = array();

			foreach ( $files as $file ) {
				$hls[] = $this->script( $file, $__FILE__, $options );
			}

			return $hls;
		}

		function pluginUrl($path, $__FILE__ = null) {
            if ( $__FILE__ === null ) {
                $__FILE__ = $this->__FILE__;
            }
		    return plugins_url($path, $__FILE__);
        }

        function createNonce() {
		    return wp_create_nonce($this->slug . $this->version);
        }

        function isValidNonce($nonce) {
		    return (isset($nonce) && wp_verify_nonce($nonce, $this->slug . $this->version));
        }

        public function getOptions($name = null) {
		    if($name == null) $name = $this->slug;

            require_once('option_collection.php');

            return new OT_Core_OptionCollection(get_option($name));
        }

        public function createMenu($addMenuPriority = 100) {
		    require_once 'admin_menu.php';

		    return new OT_Core_AdminMenu($this, $addMenuPriority);
        }

        public function applyFilters($name, $arg) {
            $args = func_get_args();
            $args[0] = "{$this->getSlug()}_{$args[0]}";
            return call_user_func_array('apply_filters', $args);
        }

        public function addFilter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
            return add_filter("{$this->getSlug()}_$tag", $function_to_add, $priority, $accepted_args);
        }

        public function addJsonAction($name, $cb = null, $capability = 'manage_options') {
            if (is_array($name)) {
                if(!$cb) $cb = $capability;
                foreach ($name as $k => $n) {
                    $this->addJsonAction($k, $n, $cb);
                }

                return;
            }
            if ($cb === null) {
                throw new Exception('cb is null');
            }

            $me = $this;
            add_action('wp_ajax_' . $this->getSlug() . '-' . $name, function () use ($me,$cb, $capability) {
                if (!$me->isValidNonce($_POST['_wpnonce']) || !current_user_can($capability)) {
                    wp_send_json_error('No access', 403);
                }

                $data = isset($_POST['data']) ? json_decode(stripslashes($_POST['data']), true) : $_POST;

                $result = call_user_func_array($cb, array(
                    $data,
                    $me
                ));

                wp_send_json($result);
            });
        }
	}
}