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
#yoast-first-time-configuration-notice,
#astra-sites-on-active {
display: none;
}
</style>';
}

add_action('admin_head', 'desativar_notificacao');

// Mostrar ID nas linhas de ação para postagens
function mostrar_id_nas_linhas_de_acao_post( $actions, $post ) {
$actions['id'] = "ID: " . $post->ID;
return $actions;
}
add_filter( 'post_row_actions', 'mostrar_id_nas_linhas_de_acao_post', 10, 2 );

// Mostrar ID nas linhas de ação para páginas
function mostrar_id_nas_linhas_de_acao_page( $actions, $post ) {
$actions['id'] = "ID: " . $post->ID;
return $actions;
}
add_filter( 'page_row_actions', 'mostrar_id_nas_linhas_de_acao_page', 10, 2 );

// Mostrar ID nas linhas de ação para custom post types
function mostrar_id_nas_linhas_de_acao_cpt( $actions, $post ) {
$actions['id'] = "ID: " . $post->ID;
return $actions;
}
add_filter( 'cpt_row_actions', 'mostrar_id_nas_linhas_de_acao_cpt', 10, 2 );

// Mostrar ID nas linhas de ação para taxonomias
function mostrar_id_nas_linhas_de_acao_taxonomia( $actions, $tag ) {
$actions['id'] = 'ID: ' . $tag->term_id;
return $actions;
}
add_filter( 'tag_row_actions', 'mostrar_id_nas_linhas_de_acao_taxonomia', 10, 2 );
add_filter( 'category_row_actions', 'mostrar_id_nas_linhas_de_acao_taxonomia', 10, 2 );


// Desabilitar Gutenberg para todos os tipos de postagens
function desativar_gutenberg_para_todos_os_tipos_de_postagens( $is_enabled, $post_type ) {
return false;
}
add_filter( 'use_block_editor_for_post_type', 'desativar_gutenberg_para_todos_os_tipos_de_postagens', 10, 2 );

// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

// Auto Shedule Posts

function pubMissedPosts() {
if (is_front_page() || is_single()) {

global $wpdb;
$now=gmdate('Y-m-d H:i:00');

//CHECK IF THERE ARE CUSTOM POST TYPES
$args = array(
'public'=> true,
'_builtin' => false,
);

$output = 'names'; // names or objects, note names is the default
$operator = 'and'; // 'and' or 'or'
$post_types = get_post_types( $args, $output, $operator );


if (count($post_types)===0) {
$sql="Select ID from $wpdb->posts WHERE post_type in ('post','page') AND post_status='future' AND post_date_gmt<'$now'";
}
else {
$str=implode ('\',\'',$post_types);
$sql="Select ID from $wpdb->posts WHERE post_type in ('page','post','$str') AND post_status='future' AND post_date_gmt<'$now'";
}

$resulto = $wpdb->get_results($sql);
 if($resulto) {
foreach( $resulto as $thisarr ) {
wp_publish_post($thisarr->ID);
}
}
}
}
add_action('wp_head', 'pubMissedPosts');

/*
* Yoast SEO Disable Automatic Redirects for
* Posts And Pages
* Credit: Yoast Development Team
* Last Tested: May 09 2017 using Yoast SEO Premium 4.7.1 on WordPress 4.7.4
*/
add_filter('wpseo_premium_post_redirect_slug_change', '__return_true' );
add_filter('wpseo_premium_term_redirect_slug_change', '__return_true' );
add_filter('wpseo_enable_notification_post_trash', '__return_false');
add_filter('wpseo_enable_notification_post_slug_change', '__return_false');
add_filter('wpseo_enable_notification_term_delete','__return_false');
add_filter('wpseo_enable_notification_term_slug_change','__return_false');