<?php

class OT_admin_plugins_hide_Tweak {
	function settings( ) {
//		add_action( 'admin_init', array( &$this, '_init' ), 0 );
		$this->_init();
	}

	function _init() {
		$els = array();

		$all_plugins = get_plugins();
		foreach($all_plugins as $file=>$info) {
			$els[$file] = $info['Name'];
		}
//var_dump($this->fields);
		$this->fields = OT_Helper::field( 'admin_plugins_hide', 'checkbox', array(
			'title'    => __( 'Hide plugins in list', OT_SLUG ),
			'desc'    => __( '', OT_SLUG ),
			'options'  => $els
		) );

		add_filter( "ut/options/tweaks", array($this, '_insertFields') );
//		add_filter( "redux/options/".OT_Helper::getCurrentOptName()."/section/".'admin_plugins', array($this, '_insertFields') );
	}

	function _insertFields($s) {
		$s['admin_plugins_hide']['fields'][] = $this->fields;
		return $s;
	}

	function tweak() {
		add_filter( 'all_plugins', array( $this, '_hide' ), 10 );
	}

	function _hide( $plugins  ) {
		if(!$this->value || !is_array($this->value)) return $plugins;

		foreach($this->value as $file=>$hidden) {
			if($hidden == 1) {
				unset($plugins[$file]);
			}
		}
		return $plugins;
	}
}