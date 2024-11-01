<?php
// protection
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

if(get_option('SH_WEB_STAT_VERSION')==null || get_option('SH_WEB_STAT_VERSION') < 124)
{
   update_option('SH_WEB_STAT_VERSION',SH_WEB_STAT_VERSION);

   $wpdb->query("ALTER TABLE ".SH_WEB_STATS_DETAILS." ADD `visitorinformations` TEXT NULL DEFAULT NULL AFTER `capture_date`, ADD `os_type` VARCHAR(255) NULL DEFAULT NULL AFTER `visitorinformations`, ADD `os_family` VARCHAR(255) NULL DEFAULT NULL AFTER `os_type`, ADD `os_name` VARCHAR(255) NULL DEFAULT NULL AFTER `os_family`,  ADD `os_version` VARCHAR(255) NULL DEFAULT NULL  AFTER `os_name`,  ADD `os_title` VARCHAR(255) NULL DEFAULT NULL  AFTER `os_version`,  ADD `device_type` VARCHAR(255) NULL DEFAULT NULL  AFTER `os_title`,  ADD `browser_name` VARCHAR(255) NULL DEFAULT NULL  AFTER `device_type`,  ADD `browser_version` VARCHAR(255) NULL DEFAULT NULL  AFTER `browser_name`,  ADD `browser_title` VARCHAR(255) NULL DEFAULT NULL  AFTER `browser_version`,  ADD `browser_chrome_original` INT(5) NULL DEFAULT '0'  AFTER `browser_title`,  ADD `browser_firefox_original` INT(5) NULL DEFAULT '0'  AFTER `browser_chrome_original`,  ADD `browser_safari_original` INT(5) NULL DEFAULT '0'  AFTER `browser_firefox_original`,  ADD `browser_chromium_version` INT(5) NULL DEFAULT '0'  AFTER `browser_safari_original`,  ADD `browser_gecko_version` INT(5) NULL DEFAULT '0'  AFTER `browser_chromium_version`,  ADD `browser_webkit_version` INT(5) NULL DEFAULT '0'  AFTER `browser_gecko_version`,  ADD `browser_android_webview` INT(5) NULL DEFAULT '0'  AFTER `browser_webkit_version`,  ADD `browser_ios_webview` INT(5) NULL DEFAULT '0'  AFTER `browser_android_webview`,  ADD `browser_desktop_mode` INT(5) NULL DEFAULT '0'  AFTER `browser_ios_webview`,  ADD `64bits_mode` INT(5) NULL DEFAULT '0'  AFTER `browser_desktop_mode`");
   
}

?>