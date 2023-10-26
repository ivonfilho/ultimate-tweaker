<?php

if (!class_exists('AMT_Menu_OriginalItem')) {
    class AMT_Menu_OriginalItem extends AMT_Menu_Item {
        /**
         * AMT_Menu_OriginalItem constructor.
         * @param $menu
         * @param $position
         * @param $parent AMT_Menu_Item|null
         * @param $menuController AMT_Menu_Controller
         */
        public function __construct($menu, $position, $parent, $menuController) {
            $this->menuController = $menuController;
            $this->menuCollection = $menuController->getOriginal();

            $this->_menu = $menu;

            $this->parent = $parent;
            $this->position = $position+0;

            parent::__construct();
        }

        public function getChanged() {
            return $this->menuController->getChanged()->findById($this->getId());
        }

        public function getOriginal() {
            throw new Exception('getOriginal exec from Original Instance' . $this->getId() );
        }
    }
}