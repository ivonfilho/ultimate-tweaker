<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists('OT_Core_OptionCollection') ) {
	class OT_Core_OptionCollection {
	    private $data = array();

        function __construct($options) {
            if(is_array($options)) $this->data = $options;
        }

        function __get($name) {
            if(!isset($this->data[$name])) return null;
            return $this->data[$name];
        }

        function getData() {
            return $this->data;
        }
    }
}