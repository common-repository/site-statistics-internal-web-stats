<?php
// protection
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
define('SH_WEB_STATS_DETAILS', $wpdb->prefix . 'sh_web_stats');
if ($wpdb->get_var("SHOW TABLES LIKE '" . SH_WEB_STATS_DETAILS . "'") != SH_WEB_STATS_DETAILS) {
    $query = "CREATE TABLE IF NOT EXISTS " .SH_WEB_STATS_DETAILS . "(
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `p_id` varchar(255) DEFAULT NULL,
        `p_type` varchar(255) DEFAULT NULL,
        `p_title` varchar(500) DEFAULT NULL,
        `tracking_id` varchar(255) DEFAULT NULL,
        `capture_date` datetime NOT NULL DEFAULT current_timestamp(),
        `visitorinformations` text DEFAULT NULL,
        `os_type` varchar(255) DEFAULT NULL,
        `os_family` varchar(255) DEFAULT NULL,
        `os_name` varchar(255) DEFAULT NULL,
        `os_version` varchar(255) DEFAULT NULL,
        `os_title` varchar(255) DEFAULT NULL,
        `device_type` varchar(255) DEFAULT NULL,
        `browser_name` varchar(255) DEFAULT NULL,
        `browser_version` varchar(255) DEFAULT NULL,
        `browser_title` varchar(255) DEFAULT NULL,
        `browser_chrome_original` int(5) DEFAULT 0,
        `browser_firefox_original` int(5) DEFAULT 0,
        `browser_safari_original` int(5) DEFAULT 0,
        `browser_chromium_version` int(5) DEFAULT 0,
        `browser_gecko_version` int(5) DEFAULT 0,
        `browser_webkit_version` int(5) DEFAULT 0,
        `browser_android_webview` int(5) DEFAULT 0,
        `browser_ios_webview` int(5) DEFAULT 0,
        `browser_desktop_mode` int(5) DEFAULT 0,
        `64bits_mode` int(5) DEFAULT 0,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
";

    $wpdb->query($query);
}

define('SH_WEB_STATS_TRACKING', $wpdb->prefix . 'sh_web_tracking');
if ($wpdb->get_var("SHOW TABLES LIKE '" . SH_WEB_STATS_TRACKING . "'") != SH_WEB_STATS_TRACKING) {
    $query = "CREATE TABLE IF NOT EXISTS " .SH_WEB_STATS_TRACKING . "(
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `visitor_ip` VARCHAR(255) NULL DEFAULT NULL,
    `country_code` VARCHAR(255) NULL DEFAULT NULL,
        `country_name` VARCHAR(255) NULL DEFAULT NULL,
        `region_code` VARCHAR(255) NULL DEFAULT NULL,
        `region_name` VARCHAR(255) NULL DEFAULT NULL,
        `city` VARCHAR(255) NULL DEFAULT NULL,
        `zip` VARCHAR(255) NULL DEFAULT NULL,
    `tracking_id` VARCHAR(255) NULL DEFAULT NULL,
    `capture_date` DATETIME NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
";

    $wpdb->query($query);
}
?>