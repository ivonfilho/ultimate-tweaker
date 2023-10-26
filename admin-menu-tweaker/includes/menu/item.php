<?php

if (!class_exists('AMT_Menu_Item')) {
    abstract class AMT_Menu_Item {
        protected $id;

        //TODO: must be protected
        public $removed = false;

        private $notCapability = '-nonca-';

        /**
         * @return string
         */
        public function getNotCapability() {
            return $this->notCapability;
        }

        /**
         * @param string $notCapability
         */
        public function setNotCapability($notCapability) {
            $this->notCapability = $notCapability;
        }

        /**
         * @return bool
         */
        public function IsRemoved() {
            return $this->removed;
        }

        /** @var  AMT_Menu_Collection */
        protected $menuCollection;

        /** @var  AMT_Menu_Controller */
        protected $menuController;

        /** @var  AMT_Menu_Item */
        protected $parent;

        //TODO: must be protected
        public $_menu;

        protected $slugClean = null;
        protected $position = null;

        public function __construct() {
            $id = $this->getCleanSlug();
            if($this->isCustom()) $id = $this->_menu[11];
            $this->id = ($this->getParent() ? $this->getParent()->getCleanSlug() . ';' : '') . $id;
        }

        /**
         * @return AMT_Menu_Item
         */
        abstract public function getOriginal();

        /**
         * @return AMT_Menu_Item
         */
        abstract public function getChanged();

        /**
         * @param $position int
         * @param $parent AMT_Menu_Item|null
         */
        public function setParent($position, $parent) {
            global $menu, $submenu;

            if($this->removed) return;
//            $parent = $parent === '0' ? null : $parent;
            //if($this->getId() == "edit.php;edit.php") return;

            $this->parent = $parent ? $parent : '0';

            if (($parent === null && isset($menu[ $position ]))
                || ($parent !== null && isset($submenu[ $parent->getSlug() ][ $position ]))
            ) {
                $position = $position + substr(base_convert(md5($this->getSlug() . $this->getName()), 16, 10), -5) * 0.00001 . '';
            }

            if($parent === null) {
                if($this->getOriginal()->getParentId() != '0') {
                    $this->_menu = &$this->convertToTop($this->_menu);
                }
//                am_e($this->getId(), $position);
                $menu[ $position ] = &$this->_menu;
                ksort($menu, SORT_NUMERIC);
            } else {
                if($this->getOriginal()->getParentId() == '0') {
                    $this->_menu = &$this->convertToSub($this->_menu);
                }

                if($this->getOriginal()->getParent() && $parent->getSlug() != $this->getOriginal()->getParent()->getSlug()) {
                    $this->toSubAnother($this->_menu);
                }
                $parentId = $parent->getSlug();

//                if(!isset($submenu[ $parentId ]) || !$submenu[ $parentId ]) {
//                    TODO: need change this also - title and ....
//                    $m = array_merge(array(),$parent->_menu);
               //     $submenu[ $parentId ][ $position - 1 ] = &$this->convertToSub($m);
//                }
                $submenu[ $parentId ][ $position ] = &$this->_menu;
                ksort($submenu[ $parentId ], SORT_NUMERIC);
            }

            $this->removed = false;
            $this->setPosition($position);
        }

        public function setPosition($position) {
            $this->position = $position;
        }

//        public function setContentBefore($content) {
//            $hookname = get_plugin_page_hookname( $this->getSlug(), '' );

//            am_d($this->getSlug(), $content);
//        }

        public function getParent() {
            return $this->parent == null ? '0' : $this->parent;
        }

        public function getParentId() {
            if(!$this->getParent()) return '0';
            return $this->getParent()->getId();
        }

        public function setName($name) {
            $this->_menu[0] = $name;
        }

        public function setTitle($title) {
            $this->_menu[3] = $title;
        }
        public function setIconClass($iconClass) {
            $this->_menu[4] = $iconClass;
        }

        private function isSeparator() {
            return strpos($this->_menu[4], 'wp-menu-separator') !== false;
        }

        public function setSeparatorStyle($style) {
            if(!$this->isSeparator()) return;
            $this->menuController->getHelper()->style('assets/separator', null, array( 'handle'=> 'am-separators' ));
            $this->_menu[4] = (isset($this->_menu[4]) ? $this->_menu[4] : '') .  ' separator-custom ' . $style;
        }

        public function setSeparatorWidth($style) {
            if(!$this->isSeparator()) return;
            $this->menuController->getHelper()->style('assets/separator', null, array( 'handle'=> 'am-separators' ));
            $this->_menu[4] = (isset($this->_menu[4]) ? $this->_menu[4] : '') .  ' ' . $style;
        }

        public function setSeparatorColor($color, $style) {
            if(!$this->isSeparator()) return;
            $cls = 'sepColor' . substr(base_convert(md5($this->getSlug() . $this->getName()), 16, 10), -5);
            $this->_menu[4] = (isset($this->_menu[4]) ? $this->_menu[4] : '') .  ' ' . $cls;

            if($style == 'separator-8') {
                $this->menuController->getHelper()->inlineStyle(
                    ".wp-admin .wp-menu-separator.separator-custom.$cls .separator",
                    "background-image: -webkit-linear-gradient(left, $color, #23282d, $color);
                    background-image: -moz-linear-gradient(left, $color, #23282d, $color);
                    background-image: -ms-linear-gradient(left, $color, #23282d, $color);
                    background-image: -o-linear-gradient(left, $color, #23282d, $color);
                    background-image: linear-gradient(left, $color, #23282d, $color);",
                    'common');
            } else if($style == 'separator-9') {
                $this->menuController->getHelper()->inlineStyle(
                    ".wp-admin .wp-menu-separator.separator-custom.$cls .separator",
                    "background-image: -webkit-linear-gradient(left, $color, #23282d);
                    background-image: -moz-linear-gradient(left, $color, #23282d);
                    background-image: -ms-linear-gradient(left, $color, #23282d);
                    background-image: -o-linear-gradient(left, $color, #23282d);
                    background-image: linear-gradient(left, $color, #23282d);",
                    'common');
            } else if($style == 'separator-10') {
                $this->menuController->getHelper()->inlineStyle(
                    ".wp-admin .wp-menu-separator.separator-custom.$cls .separator",
                    "background-image: -webkit-linear-gradient(left, $color, #23282d, #23282d);
                    background-image: -moz-linear-gradient(left, $color, #23282d, #23282d);
                    background-image: -ms-linear-gradient(left, $color, #23282d, #23282d);
                    background-image: -o-linear-gradient(left, $color, #23282d, #23282d);
                    background-image: linear-gradient(left, $color, #23282d, #23282d);",
                    'common');
            } else {
                $this->menuController->getHelper()->inlineStyle(
                    ".wp-admin .wp-menu-separator.separator-custom.$cls .separator",
                    "border-color: $color !important;",
                    'common');
            }
        }

        public function setIconColor($color) {
            $cls = 'color' . substr(base_convert(md5($this->getSlug() . $this->getName()), 16, 10), -5);

            $this->_menu[4] = (isset($this->_menu[4]) ? $this->_menu[4] : '') .  ' '.$cls;

            $this->menuController->getHelper()->inlineStyle(
                ".wp-admin .menu-top.$cls .wp-menu-image.dashicons-before:before",
                "color: $color !important;",
                'common');
        }

        public function setCustomIcon($customIcon) {
            if(isset($customIcon['url']) && $customIcon['url']) {
                $this->setIconUrl($customIcon['url']);
                $this->menuController->getHelper()->inlineStyle('.wp-menu-image img', 'max-height:18px;max-width:18px;', 'admin-menu');
            } else if(isset($customIcon['fontName']) && $customIcon['fontName'] &&
                isset($customIcon['iconChar']) && $customIcon['iconChar']
            ) {
                $this->menuController->getHelper()->style('assets/icons/icon-fonts', null, array( 'handle'=> 'am-icon-fonts' ));

                $fontName = $customIcon['fontName'];
                $iconChar = $customIcon['iconChar'];
                $iconCls = "dashicons--$fontName-$iconChar";

                $this->menuController->getHelper()->inlineStyle(
                    ".wp-admin #adminmenu #{$this->_menu[5]} .wp-menu-image.$iconCls:before,".
                    ".wp-admin #adminmenu .menu-top .wp-menu-image.$iconCls:before",
                        "font-family: \"$fontName\" !important;".
                        'content: "\\' . $iconChar . '" !important;',
                    'am-icon-fonts');

                $this->setIconUrl( $iconCls );
            }
        }

        public function setIconUrl($iconUrl) {
            $this->_menu[6] = $iconUrl;
        }

        public function setHookName($hookName) {
            $this->_menu[5] = $hookName;
        }

        /**
         * @param $slug
         * @return AMT_Menu_Item[]
         */
        public function getChilds($withRemoved = false) {
            $result = array();

            foreach ($this->menuCollection->getItems() as $i) {
                if ($i->getParentId() == $this->getSlug()) {
                    if(!$i->isRemoved() || $withRemoved) {
                        $result[] = $i;
                    }
                }
            }
            return $result;
        }

        public function setCapability($capability) {
            $this->_menu[1] = $capability;
        }

//        not possible after
        public function setSlug($slug) {
//            return;
            $this->slugClean = null;
            $this->_menu[2] = $slug;
        }

        public function getName($noTags = false) {
            if($noTags) {
                $last = $this->_menu[0];
                $string = $last;
                while(strpos($last, '<') !== false && $string !== ($last = preg_replace('/<([^>]+?)([^>]*?)>([^<]*?)<\/\1>|<([^>]+?)\/>/si', '', $last))) {
                    $string = $last;
                }
                return $last;
            }
            return $this->_menu[0];
        }

        public function getTitle() {
            return isset($this->_menu[3]) ? $this->_menu[3] : null;
        }

        public function isCustom() {
            return isset($this->_menu[10]) && $this->_menu[10] == 'custom';
        }

        public function getIconClass() {
            return isset($this->_menu[4]) ? $this->_menu[4] : null;
        }

        public function getIconUrl() {
            return isset($this->_menu[6]) ? $this->_menu[6] : null;
        }

        public function getHookName() {
            return isset($this->_menu[5]) ? $this->_menu[5] : null;
        }

        public function getPosition() {
            return $this->position;
        }

        public function getCapability() {
            return $this->_menu[1];
        }

        public function getCleanSlug() {
            if(!$this->slugClean) {
                $slug = htmlspecialchars_decode($this->_menu[2]);
                $slug = str_ireplace(admin_url(), '', $slug);
                $this->slugClean = remove_query_arg('return', $slug);
            }
            return $this->slugClean;
        }
        public function getSlug() {
            return $this->_menu[2];
        }
        public function getId() {
            return $this->id;
        }

        public function remove($keep = false) {
            if($this->removed) return;
            $this->removed = !$keep;

            if($this->getParent()) {
//                am_e($this->toArray());
                return remove_submenu_page($this->getParent()->getSlug(), $this->getSlug());
            } else {
                return remove_menu_page($this->getSlug());
            }
        }

        public function toSubAnother($m) {
            $me = $this;
            add_action('at_parent_file', function($parent_file) use($me, $m) {
                global $menu, $submenu, $parent_file, $typenow, $pagenow, $self, $current_screen, $submenu_file, $plugin_page;
//                am_e('2', $submenu_file);
                $pp = $parent_file;
                if(strpos($pp, '.php') === false) $pp = 'admin.php';
                if($submenu_file == $m[2] || $self == $m[2] || $pp.'?page='.$plugin_page == $m[2]) {
                    foreach ($menu as $men) {
                        if($men[2] == $m[2]) {
//                            if(count($submenu[$men[2]]) <= 1) {
                                $pagenow = '';
                                return '';
//                            } else {
//                                $self = '';
//                                return $this->getParent()->getSlug();
//                            }
                        }
                    }
                    return $me->getParent()->getSlug();
                }
                return $parent_file;
            });
        }

        public function &convertToTop(&$m) {
            global $wp_filter;
            $menu_slug = $m[2];

//            $hooknameOriginal = get_plugin_page_hookname( $menu_slug, $parentSlug );
            $hookname = get_plugin_page_hookname( $menu_slug , '' );
//die($menu_slug);
//            $m[2] = $menu_slug . '-am';
//            $m[5] = $menu_slug . '-am';

//            am_d($wp_filter[$hooknameOriginal]);
//            if(isset($wp_filter[$hooknameOriginal])) {

//                add_action($hookname, function() {
//                    die('ddd');
//                });
//                $wp_filter[$hookname] = $wp_filter[$hooknameOriginal];
//                unset($wp_filter[$hooknameOriginal]);
//                $m[5] = $hookname;
//                $m[2] = 'admin.php?page=' . $m[2];


//            } else {
//                var_dump($);
//            }

            $icon_url = $m[6] = isset($m[6]) && $m[6] ? $m[6] : 'dashicons-admin-generic';
            $icon_class = 'menu-icon-generic ';

            $m[4] = 'menu-top ' . $icon_class . $hookname;

            global $current_screen;
           // am_e($current_screen);
//            add_action('at_submenu_file', function($submenu_file) use($m) {
//                am_e('2', $submenu_file);
//                return $submenu_file;
//            });
            add_action('at_parent_file', function($parent_file) use($m) {
                global $menu, $pagenow, $parent_file, $typenow, $self, $current_screen, $submenu_file, $plugin_page;
//                am_e('2', $submenu_file);
                $pp = $parent_file;
                if(strpos($pp, '.php') === false) $pp = 'admin.php';
                if($submenu_file == $m[2] || $self == $m[2] || $pp.'?page='.$plugin_page == $m[2]) {
                    $typenow = '--';
                    $self = $m[2];
                    $parent_file = $m[2];

                    return $m[2];
                }

//                if($current_screen->base == 'edit-tags') {
//                    $url = "$current_screen->base.php?taxonomy=$current_screen->taxonomy";
//                    if($url == $m[2]) {
//                        return $m[2];
//                    }
//                }

//                global $typenow, $self;
//
//                if($self == $m[2]) {
//                    return '';
//                }
                return $parent_file;
            });

            return $m;
        }

        public function &convertToSub(&$m) {
            $m[4] = preg_replace('@menu-top-last|menu-top@si', '' ,$m[4]);

            $me = $this;
            add_action('at_parent_file', function($parent_file) use($me, $m) {
                global $parent_file, $typenow, $self, $current_screen, $submenu_file;

                if("$self?page=$parent_file" == $m[2]) {
                    $self = $me->getParent()->getSlug();
                    $submenu_file = $m[2];
                    return '';
                }
                return $parent_file;
            });

            return $m;
        }

        public function toArray() {
            $e = $this;
            $r = array(
                'id'         => $e->getId(),
                'parentId'   => $e->getParentId(),
                'name'       => $e->getName(true),
                'title'      => $e->getTitle(),
                'slug'       => $e->getSlug(),
                'cleanSlug'  => $e->getCleanSlug(),
                'position'   => $e->getPosition(),
                'capability' => $e->getCapability(),
                'notCapability' => $e->getNotCapability(),
                'iconClass'  => $e->getIconClass(),
                'iconUrl'    => $e->getIconUrl(),
                'hookName'   => $e->getHookName(),
            );
            return $r;
        }

        public function __toString() {
            return wp_json_encode($this->toArray(), 0, 1);
        }
    }
}