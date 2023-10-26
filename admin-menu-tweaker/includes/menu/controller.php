<?php

if (!class_exists('AMT_Menu_Controller')) {
    class AMT_Menu_Controller {
        /** @var  AMT_Core_Helper */
        private $helper;

        /** @var  AMT_Menu_Collection */
        private $changed;

        /** @var  AMT_Menu_Collection */
        private $original;

        /**
         * @return AMT_Core_Helper
         */
        public function getHelper() {
            return $this->helper;
        }

        /**
         * @return AMT_Menu_Collection
         */
        public function getChanged() {
            return $this->changed;
        }

        /**
         * @return AMT_Menu_Collection
         */
        public function getOriginal() {
            return $this->original;
        }

        public function __construct($helper, &$menu, &$submenu) {
            require_once('collection.php');
            require_once('item.php');
            require_once('original_item.php');
            require_once('changed_item.php');

            $this->helper = $helper;
            $this->original = new AMT_Menu_Collection($helper);
            $this->changed = new AMT_Menu_Collection($helper);

            foreach ($menu as $menuPosition => &$menuItem) {
                $this->menuDoctor($menuItem);

                $changedItem = new AMT_Menu_ChangedItem($menuItem, $menuPosition, null, $this);
                $originalItem = new AMT_Menu_OriginalItem($menuItem, $menuPosition, null, $this);

                $this->changed->add($changedItem);
                $this->original->add($originalItem);

                if (isset($submenu[ $originalItem->getSlug() ])) {
                    foreach ($submenu[ $originalItem->getSlug() ] as $submenuPosition => &$submenuItem) {
                        $changedSubItem = new AMT_Menu_ChangedItem($submenuItem, $submenuPosition, $changedItem, $this);
                        $originalSubItem = new AMT_Menu_OriginalItem($submenuItem, $submenuPosition, $originalItem, $this);
//
                        $this->changed->add($changedSubItem);
                        $this->original->add($originalSubItem);
                    }
                }
            }

            // for non-admin manual items
            if(current_user_can('list_users')) {
                $profile = new AMT_Menu_OriginalItem(
                    array(__('Profile'), 'read', 'profile.php', '', 'menu-top menu-icon-users', 'menu-users', 'dashicons-admin-users'),
                    13, null, $this);
                $profile->setNotCapability('list_users');

                $yourProfile = new AMT_Menu_OriginalItem(
                    array(__('Your Profile'), 'read', 'profile.php'),
                    15, $profile, $this);
                $this->original->add($profile);
                $this->original->add($yourProfile);
            }
        }

        public function doChanges($options) {
//            global $menu;
//            am_d($menu);
//            am_d($options);
            if (is_array($options)) {
                foreach ($options as $id => $change) {
                    $menu = $this->changed->findById($id);
                    if(!$menu) continue;
                    foreach ($change as $key => $value) {
                        switch ($key) {
                            case 'name':
                                $menu->setName($value);
                                break;
                            case 'slug':
                                //not possible here
                                $menu->setSlug($value);
                                break;
                            case 'capability':
                                $menu->setCapability($value);
                                break;
                            case 'customIcon':
                                $menu->setCustomIcon($value);
                                break;
                            case 'separatorStyle':
                                $menu->setSeparatorStyle($value);
                                break;
                            case 'separatorWidth':
                                $menu->setSeparatorWidth($value);
                                break;
                            case 'separatorColor':
                                $menu->setSeparatorColor($value, isset($change['separatorStyle']) ? $change['separatorStyle'] : '');
                                break;
//                            case 'contentBefore':
//                                $menu->setContentBefore($value);
//                                break;
                            case 'colorIcon':
                                $menu->setIconColor($value);
                                break;
                            case 'title'://TODO: not working - need do before <body>, now after
                                $menu->setTitle($value);
                                break;
                            case 'isHidden':
                                ($value > 0) && $menu->remove();
                                break;
                            default:
                                break;
                        }
                    }


                    if(isset($change['position']) || isset($change['parentId'])) {
//                        am_e($menu->getSlug());
                        $this->move($menu, $change);
                    }
                }
            }
        }

        /**
         * remove trash(absolute urls)
         * @param $menu
         */
        private function menuDoctor(&$menu) {
            $adminUrl = admin_url();
            if (strpos($menu[2], $adminUrl) === 0) {
                $menu[2] = substr($menu[2], strlen($adminUrl));
            }
            $adminStartUrl = 'admin.php?page=';
            if (strpos($menu[2], $adminStartUrl) === 0 && strpos($menu[2], '&') === -1) {
                $menu[2] = substr($menu[2], strlen($adminStartUrl));
            }
        }

        /**
         * @param $changed AMT_Menu_Item
         * @param $new
         */
        public function move($changed, $new) {
//            global $menu, $submenu;
            $original = $changed->getOriginal();
//am_e($new);
            $newParentId = null;
            $newPosition = null;

            $oldParentId = $changed->getParentId();
            $oldPosition = $changed->getPosition();

            if (isset($new['parentId']) && $new['parentId'] !== null) {
                $newParentId = $new['parentId'] . '';
            }
            if (isset($new['position']) && $new['position'] !== null) {
                $newPosition = $new['position'];
//                am_e($changed->toArray(), $newPosition);
                $newPosition = !is_float($newPosition) ? $newPosition : $newPosition . '';
            }

            $newParentId = $newParentId === null ? $oldParentId : $newParentId;
            $newPosition = $newPosition === null ? $oldPosition : $newPosition;

            // Take(remove) menu from current
            $changed->remove(true);

            if ($oldParentId == $newParentId) {
                // Same parent for sub - we change position only - 5
                if ($newParentId != '0') {
                    $changed->setParent($newPosition, $changed->getParent());
                } else {// Same top level - we change position only - 5
                    $changed->setParent($newPosition, null);
                }
            } else {
                $sl = $changed->getOriginal()->getSlug();
                if (strpos($sl, '?') === false && strpos($sl, '://') === false && strpos($sl, '.php') === false
                    && $changed->getParent()) {
                    $parentSlug = $changed->getParent()->getSlug();
                    if(strpos($parentSlug, '.php') === false) $parentSlug = 'admin.php';
                    $changed->setSlug($parentSlug . '?page=' . $sl);
                }

                // Move from sub to diff sub menu
                if ($newParentId != '0') { // move to another sub menu
//                    global $menu;
//                    am_d($newParentId, $menu);
                    $p = $this->original->findById($newParentId);
//                    am_d($p);
                    if(!$p) {
//                        am_d($changed->toArray());
                        $changed->setParent($newPosition, $original->getParent()); //Return to original place
                    } else {
                        $changed->setParent($newPosition, $p->getChanged()); //Set new parent
                    }
                } else if ($newParentId == '0') {//sub to top
//                    am_e($changed->toArray(), $newPosition);
                    $changed->setParent($newPosition, null);
                }
            }
        }

        public function getOriginalArray() {
            return $this->original->toArray();
        }
    }
}