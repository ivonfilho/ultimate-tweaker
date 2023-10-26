<?php
/**
 * Created by Amino-Studio.
 * Url: http://amino-studio.com/
 * License: http://amino-studio.com/license/
 */

if (!class_exists('OT_Starter_SettingsLoadTweaks')) {
    class OT_Starter_SettingsLoadTweaks {
        /**
         * @param $helper OT_Core_Helper
         */
        static function start($helper) {
            new OT_Starter_SettingsLoadTweaks($helper);
        }

        /** @var  OT_Core_Helper */
        public $helper;

        public function __construct($helper) {
            $this->helper = $helper;

            $helper->addFilter("setting_data", array($this, 'loadData'));
        }

        public function loadData($data) {
            $this->helper->requireOnce('includes/only/helper.php');
            $this->helper->requireOnce('includes/only/section.php');
            $this->helper->requireOnce('includes/only/menu_page.php');

            $this->menu = new OT_Only_MenuPage($this->helper->getSlug());
            $this->prepareData();

            $data['sections'] = $this->menu->_sections;
            $data['tweaks'] = $this->menu->_tweak_fields;
            $data['tweak_groups'] = OT_Only_Meta::getTweakGroupsMeta();

            return $data;
        }

        function prepareData() {

            $menu = $this->menu;

            $this->helper->requireOnce('includes/only/meta.php');
            $this->helper->requireOnce('includes/only/data.php');

            $sections = OT_Only_Data::getSections();
            $i = 0;
            foreach($sections as $section_ID => $tweaks) {
                $sectionMeta = OT_Only_Meta::getSectionMeta($section_ID);
                $tweakMeta = OT_Only_Meta::getTweaksMeta();
                if(!$sectionMeta) continue;
//				if($role && @$sectionMeta->visibility != 'all_roles') continue;

                $section = $menu->addSection( $section_ID );
                $section->visibility = isset($sectionMeta->visibility) ? $sectionMeta->visibility : null;
                $section->icon = isset($sectionMeta->icon) ? $sectionMeta->icon : null;
                $section->icon_type = isset($sectionMeta->icon_type)? $sectionMeta->icon_type : null;
                $section->title = $sectionMeta->title;
                $section->id = isset($sectionMeta->id) ? $sectionMeta->id : null;
                $section->parent_id =  isset($sectionMeta->parent_id) ? $sectionMeta->parent_id : 0;

                foreach($tweaks as $tweak_ID => $applyPlace) {
                    if(is_array($applyPlace)) {
                        continue;
                    }

                    $this->helper->requireOnce("sections/{$section_ID}/{$tweak_ID}/tweak.php");
                    $tweakCls = "OT_{$tweak_ID}_Tweak";
                    $tweak = new $tweakCls();
                    $i++;
                    if(method_exists($tweak, 'isVisible') && !$tweak->isVisible()) continue;
                    if(method_exists($tweak, 'isAvailable') && !$tweak->isAvailable()) continue;

                    $section->addTweak($tweak_ID);

                    $tweakFields = $tweak->settings();
                    if($tweakFields == null) {
                    } else if( isset($tweakFields['id']) ) {
                        $tweakFields = $this->prepareField($tweakFields);
                        $menu->addTweakFields( $tweak_ID, array($tweakFields) );
                    } else {
                        foreach($tweakFields as &$tweakField) {
                            $tweakField = $this->prepareField($tweakField);
                        }
                        $menu->addTweakFields( $tweak_ID, $tweakFields );
                    }

                    if(isset($tweakMeta[$tweak_ID])) {//
                        if(!isset($menu->_tweak_fields[ $tweak_ID ])) $menu->_tweak_fields[ $tweak_ID ] = array();
                        $menu->_tweak_fields[ $tweak_ID ] = array_merge($menu->_tweak_fields[ $tweak_ID ], $tweakMeta[$tweak_ID]);
                    }

                }
            }

            $this->menu->_tweak_fields = apply_filters("ut/options/tweaks", $this->menu->_tweak_fields);
        }


        function prepareField($field) {
            if( $field['type'] == 'select' ) {
                if(isset($field['data'])) {
                    $data  = array();
                    if($field['data'] == 'pages') {
                        $pages = get_pages();

                        if ( ! empty( $pages ) ) {
                            foreach ( $pages as $page ) {
                                $data[ $page->ID ] = $page->post_title;
                            }
                        }
                        $field['default'] = '6';
                    } else if($field['data'] == 'menus') {
                        $menus = wp_get_nav_menus();
                        if ( ! empty( $menus ) ) {
                            foreach ( $menus as $item ) {
                                $data[ $item->term_id ] = $item->name;
                            }
                        }
                    } else if($field['data'] == 'categories') {
                        $cats = get_categories();
                        if ( ! empty( $cats ) ) {
                            foreach ( $cats as $cat ) {
                                $data[ $cat->term_id ] = $cat->name;
                            }
                        }
                    } else if($field['data'] == 'tags') {
                        $tags = get_tags();
                        if ( ! empty( $tags ) ) {
                            foreach ( $tags as $tag ) {
                                $data[ $tag->term_id ] = $tag->name;
                            }
                        }
                    } else {
//						var_dump( $field );
                    }
                    $field['options'] = $data;
                }
            }
            return $field;
        }
    }
}