<?php

if (!class_exists('AMT_Menu_Collection')) {
    class AMT_Menu_Collection {
        /** @var  AMT_Core_Helper */
        private $helper;

        /** @var  AMT_Menu_Item[] */
        private $items;

        /**
         * @return AMT_Menu_Item[]
         */
        public function getItems() {
            return $this->items;
        }

        public function __construct($helper) {
            $this->helper = $helper;
        }

        public function add($item) {
            $this->items[] = $item;
        }

        /**
         * @param $slug
         * @return AMT_Menu_Item[]
         */
        public function allBySlug($slug) {
            $result = array();
            foreach ($this->items as $i) {
                if ($i->getSlug() == $slug || $i->getCleanSlug() == $slug) {
                    $result[] = $i;
                }
            }

            return $result;
        }

        public function findBySlug($slug) {
            //am_e($this->toArray());
            foreach ($this->items as $i) {
                if ($i->getSlug() == $slug || $i->getCleanSlug() == $slug) {
                    return $i;
                }
            }

            return null;
        }

        /** @return AMT_Menu_Item */
        public function findById($id) {
            if(!$this->items) return null;
            foreach ($this->items as $i) {
                $iid = $i->getId();
                $iid = preg_replace('@vc-welcome;@', 'vc-general;', $iid);
                $iid = preg_replace('/^vc-welcome$/', 'vc-general', $iid);

                if ($iid == $id) {
                    return $i;
                }
            }

            return null;
        }

        /**
         * @return AMT_Menu_Item[]
         */
        public function getFirstLevelItems() {
            $m = array();
            foreach ($this->items as $i) {
                if (!$i->getParent()) $m[] = $i;
            }

            return $m;
        }

        public function toArray() {
            $r = array();
            foreach ($this->items as $item) {
                if($item->isCustom()) continue;
                $r[] = $item->toArray();
            }

            return $r;
        }
    }
}