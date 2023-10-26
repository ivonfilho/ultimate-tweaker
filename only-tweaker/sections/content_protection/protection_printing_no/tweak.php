<?php

class OT_protection_printing_no_Tweak {
	function settings() {
		return OT_Helper::switcher( 'protection_printing_no', array(
			'title' => __( 'Disable Printing button', OT_SLUG )
		) );
	}

	function tweak() {
		OT_Helper::$_->script('dis-print', __FILE__, array('deps'=>array("jquery")) );
		echo '<style type="text/css" media="print">'.
                '* { display: none; }'.
            '</style>';
	}
}