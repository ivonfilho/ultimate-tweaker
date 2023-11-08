<?php



class OT_login_main_color_Tweak {

function settings( ) {
return OT_Helper::field( 'login_main_color', 'color', array(
'title'    => __( 'Button & links color', OT_SLUG ),
'desc'    => __( '', OT_SLUG ),
'transparent'  => false,
) );
}

function tweak() {
add_action('login_head', array($this, '_do'));
}

function _do() {

echo '<style type="text/css">:root {--wp-preset--cor--default: '.$this->value.';}';
echo '</style>';
}
}