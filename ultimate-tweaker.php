<?php
/*
Plugin Name: HostPress
Plugin URI: https://hostpress.com.br/
Description: Plugin de gerenciamento de WordPress por HostPress.com.br
Version: 4.0
Text Domain: hostpress
Author: HostPress <contato@hostpress.com.br>
Author URI: https://hostpress.com.br/
*/

if (!defined('ABSPATH')) {
die('-1');
}

define('FILE', __FILE__);

define('UT_NAME', 'HostPress');
define('UT_VERSION', '1.2');
define('UT_SLUG', 'hostpress');

if (!class_exists('ultimate_tweaker_Plugin_File')) {
class ultimate_tweaker_Plugin_File
{
/** @var UT_Core_Helper*/
public $helper;

/**
 * @return UT_Core_Helper
 */
public function getHelper()
{
return $this->helper;
}

/**
 * @return UT_Settings
 */
public function getSettings()
{
return $this->settings;
}

private $settings;

function __construct()
{
require_once(plugin_dir_path(__FILE__) . 'includes/core/helper.php');

$this->helper = new UT_Core_Helper(
__FILE__,
UT_NAME,
UT_SLUG,
UT_VERSION,
(defined('WP_DEBUG') && WP_DEBUG == true)
);

if (!$this->helper->isSupported())
return;

//
//

$this->helper->requireOnce('only-tweaker/only-tweaker.php');
$this->helper->requireOnce('admin-menu-tweaker/admin-menu-tweaker.php');

if (is_admin()) {
if ($this->helper->isPluginActive('only-tweaker')) {
$this->helper->deactivatePlugin('only-tweaker');
}
if ($this->helper->isPluginActive('admin-menu-tweaker')) {
$this->helper->deactivatePlugin('admin-menu-tweaker');
}

add_action('admin_menu', array($this, 'addMenu'), 10);
add_action('network_admin_menu', array($this, 'addMenu'), 10);
}
}

function addMenu()
{
add_menu_page(
$this->helper->getName(),
$this->helper->getName(),
'manage_options',
'only-tweaker',
null,
//function() { echo '<script> window.location="'.admin_url('/admin.php?page=only-tweaker').'"; </script> '; exit; },
$this->helper->pluginUrl('assets/admin-icon.png'),
null
);
}
}
}

new ultimate_tweaker_Plugin_File();

// Hide Dashboard Widget e Notices
function esconder_widgets_dashboard(){
remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'normal' );
remove_meta_box( 'easy_wp_smtp_reports_widget_lite', 'dashboard', 'normal' );
remove_meta_box( 'e-dashboard-overview', 'dashboard', 'normal' );
remove_meta_box( 'wc_admin_dashboard_setup', 'dashboard', 'normal' );
}
add_action( 'wp_dashboard_setup', 'esconder_widgets_dashboard' );


function desativar_notificacao() {
echo '<style type="text/css">
.notice.e-notice.e-notice--dismissible.e-notice--extended,
.sbi_notice_op.sbi_notice.sbi_discount_notice,
#yoast-first-time-configuration-notice {
display: none;
}
</style>';
}

add_action('admin_head', 'desativar_notificacao');