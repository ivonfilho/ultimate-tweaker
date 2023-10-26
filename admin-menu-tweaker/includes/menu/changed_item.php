<?php

if (!class_exists('AMT_Menu_ChangedItem')) {
    class AMT_Menu_ChangedItem extends AMT_Menu_Item {
        /**
         * AMT_Menu_ChangedItem constructor.
         * @param $menu
         * @param $position
         * @param $parent AMT_Menu_Item|null
         * @param $menuController AMT_Menu_Controller
         */
        public function __construct(&$menu, $position, $parent, $menuController) {
            $this->menuController = $menuController;
            $this->menuCollection = $menuController->getChanged();

            $this->_menu = &$menu;

            $this->parent = $parent;
            $this->position = $position+0;

            parent::__construct();
        }

        public function getOriginal() {
            return $this->menuController->getOriginal()->findById($this->getId());
        }

        public function getChanged() {
            throw new Exception('getChanged exec from Changed Instance' . $this->getId() );
        }
    }
}