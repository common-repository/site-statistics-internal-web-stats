<?php
/**
 * Plugin Name: Site Statistics - Internal Web Stats
 * Description: A complete statistics of your website. Site Statistics - Internal Web Stats Plugin gives you a glimpse of the statistics of posts, pages and comments you have in your website. Also, it shows the server information too.
 * Tags: website stats, server informations, dashboard widget, dashboard stats, website statistics, pages count, posts count, page views statistics
 * Version: 1.2.4
 * Stable tag: 1.2.4
 * Author: SpiceHook Solutions
 * Author URI: https://www.spicehook.com
 * Text Domain: sh-language
 * Requires at least: 4.6.0
 * Tested up to: 6.1
 * Requires PHP: 5.6
 * PHP Version Tested up to: 8.0
 * License: GPLv2
 */
// protection
if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly
define('SH_WEB_STAT_VERSION', 124);
function sh_web_stats_register_activation_hook()
{
    if (version_compare(get_bloginfo("version"), "4.5", "<")) {
        wp_die("Please update WordPress to use this plugin");
    }
    update_option('SH_WEB_STAT_VERSION', SH_WEB_STAT_VERSION);
}
register_deactivation_hook(__FILE__, 'sh_web_stats_deactivate');
register_uninstall_hook(__FILE__, 'sh_web_stats_deactivate_uninstall');
function sh_web_stats_deactivate_uninstall()
{
    delete_option('SH_WEB_STAT_VERSION');
}
function sh_web_stats_deactivate()
{
    delete_option('SH_WEB_STAT_VERSION');
}
function sh_web_stats_init()
{
    register_activation_hook(__FILE__, "sh_web_stats_register_activation_hook");
    add_action("wp_dashboard_setup", "sh_web_stats_server_informations");
    add_action("admin_enqueue_scripts", "sh_web_stats_styles");
}
sh_web_stats_init();
function sh_web_stats_server_informations()
{
    global $wp_meta_boxes;

    wp_add_dashboard_widget('sh_web_stats_visit_info', 'Visitors Summary', 'sh_web_stats_visitor_summary_informations_func');

    wp_add_dashboard_widget('sh_web_stats_server_info', 'Server Information', 'sh_web_stats_server_informations_func');
    wp_add_dashboard_widget('sh_web_stats_pages_info', 'Blog Posts & Pages Informations', 'sh_web_stats_pages_informations_func');

    wp_add_dashboard_widget('sh_web_stats_server_extended_info', 'Server Extended Informations', 'sh_web_stats_server_extended_informations_func');
    // Makes sure the plugin is defined before trying to use it
    $need = false;
    if (!function_exists('is_plugin_active_for_network')) {
        require_once ABSPATH . '/wp-admin/includes/plugin.php';
    }
// multisite && this plugin is locally activated - Woo can be network or locally activated
    if (is_multisite() && is_plugin_active_for_network(plugin_basename(__FILE__))) {
        // this plugin is network activated - Woo must be network activated
        $need = is_plugin_active_for_network('woocommerce/woocommerce.php') ? false : true;
// this plugin runs on a single site || is locally activated
    } else {
        $need = is_plugin_active('woocommerce/woocommerce.php') ? false : true;
    }
    if ($need === false) {
        wp_add_dashboard_widget('sh_web_stats_products_info', 'Woocommerce Product Informations', 'sh_web_stats_products_informations_func');
    }
}

function sh_web_stats_styles()
{
    wp_register_style("sh_web_stats_admin_style", plugin_dir_url(__FILE__) . "sh_webstats.css?v=" . rand());
    wp_enqueue_style("sh_web_stats_admin_style");

    wp_register_script("sh_web_stats_data_table", plugin_dir_url(__FILE__) . "shinc/js/datatables.min.js?v=" . rand());
    wp_enqueue_script("sh_web_stats_data_table");

    wp_register_style("sh_web_stats_data_table_style", plugin_dir_url(__FILE__) . "shinc/js/datatables.min.css?v=" . rand());
    wp_enqueue_style("sh_web_stats_data_table_style");
}
add_action('plugins_loaded', 'sh_web_stats_plugins_update');
function sh_web_stats_plugins_update()
{
    include plugin_dir_path(__FILE__) . 'update.php';
}

require 'shinc/sh-widgets.php';
require 'shinc/sh-web-tables.php';
require 'shinc/sh-capture-visitors.php';

add_action('wp_ajax_sh_web_stats_memory_usage', 'sh_web_stats_memory_usage');
add_action('wp_ajax_nopriv_sh_web_stats_memory_usage', 'sh_web_stats_memory_usage');

function sh_web_stats_memory_usage()
{
   

    $load = sys_getloadavg();
    $cpuload = $load[0];   
   
    $return['cpuload']=$cpuload.' %';
    $return['memused'] = (function_exists('memory_get_usage') ? round(memory_get_usage() / 1024 / 1024, 2) : 0).' MB';
    

    echo json_encode($return);
    wp_die();
}

function reIndexArray( $arr, $startAt=0 )
{
    return ( 0 == $startAt )
        ? array_values( $arr )
        : array_combine( range( $startAt, count( $arr ) + ( $startAt - 1 ) ), array_values( $arr ) );
}

add_action('admin_footer', 'sh_web_stats_ajax_inc');
    function sh_web_stats_ajax_inc()
    {

        wp_enqueue_script('ajax-script-sh-web-stats', plugins_url('/sh_web_stats_ajax_inc.js?v=' . rand(), __FILE__), array('jquery'));
        // in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
        wp_localize_script('ajax-script-sh-web-stats', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

      

    }
add_action('admin_footer', 'sh_web_stats_ajax_calls');

    function sh_web_stats_ajax_calls()
    {
        ?>
            <script>
                if(jQuery('#sh_web_stats_server_extended_info').length)
                {
                 setInterval(function(){
                    sh_web_stats_memory_usage();
                },2500);
            }
                </script>
        <?php
    }

    /**
 * Register a Page Views page.
 */
function sh_web_stats_page_stats_reports() {
	add_menu_page(
		__( 'Web Stats', 'sh-language' ),
		'Your website statistics',
		'manage_options',
		'sh_overview_stats',
		'sh_overview_stats_func',
		plugin_dir_url(__FILE__).'shinc/images/pagestats.png',
		6
	);

    add_submenu_page(
        'sh_overview_stats',
		__( 'Page Stats', 'sh-language' ),
		'Page Views Statistics',
		'manage_options',
		'sh_page_stats_stats',
		'sh_page_stats_func'
	);

    add_submenu_page(
        'sh_overview_stats',
		__( 'Posts Stats', 'sh-language' ),
		'Posts Views Statistics',
		'manage_options',
		'sh_posts_stats_stats',
		'sh_posts_stats_func'
	);
}
add_action( 'admin_menu', 'sh_web_stats_page_stats_reports' );

function sh_overview_stats_func()
{
    require 'shinc/sh-overview-stats.php';
}
function sh_posts_stats_func()
{
    require 'shinc/sh-post-stats.php';
}
function sh_page_stats_func()
{
    require 'shinc/sh-page-stats.php';
}
    ?>