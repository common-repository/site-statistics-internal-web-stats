<?php
// protection
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



function sh_site_stats_get_ip()
{
    $ip = null;
    $deep_detect=true;

    if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }

            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }

        }
    }

    return $ip;
}
add_action('wp_head','sh_site_stats_web_head');
function sh_site_stats_web_head()
{
    if(is_admin( ))
    return;

    global $post;
    global $wpdb;

    require 'browserinfo/BrowserDetection.php';
    $Browser = new foroco\BrowserDetection();
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    // Get all possible environment data (array):

    if($useragent!='')
    {
    $result = $Browser->getAll($useragent);


        //if($_SERVER['SERVER_NAME']!='spicehook.com')
        //{
        $data['ds'] = base64_encode($_SERVER['SERVER_NAME']);
        $data['visitor_ip'] = sh_site_stats_get_ip();
		$post_slug = $post->post_name;
		
		 // send API request via cURL
         $ch = curl_init();
         $endpoint='https://shget.spicehook.com/?dsver='.base64_encode($_SERVER['SERVER_NAME']);
        
         curl_setopt($ch, CURLOPT_URL, $endpoint);
         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Connection: Keep-Alive'));
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     
         $response = curl_exec($ch);
     
         curl_close($ch);
        

         $response=(json_decode($response,true));

      
         if(!empty($response))
         {
            $existingipinfo=$wpdb->get_row("SELECT * FROM " . SH_WEB_STATS_TRACKING . " WHERE visitor_ip='" .  $data['visitor_ip'] . "' AND country_code='".$response['country_code']."' AND region_name='".esc_sql($response['region_name'])."' AND city='".esc_sql($response['city'])."' AND zip='".$response['zip']."'", ARRAY_A);

            if(empty($existingipinfo))
            {
                $wpdb->query("INSERT INTO " . SH_WEB_STATS_TRACKING . "(visitor_ip,country_code,country_name,region_code,region_name,city,zip,tracking_id,capture_date) VALUES ('" .  $data['visitor_ip'] . "','". $response['country_code']."','" . esc_sql( $response['country_name']) . "','". $response['region_code']."','".esc_sql( $response['region_name'])."','".esc_sql($response['city'])."','".$response['zip']."',UUID(),'".current_time('mysql')."')");
                $lastid = $wpdb->insert_id;
            }
            else
            {
                $lastid=$existingipinfo['id'];
            }
            

            $uuidetails = $wpdb->get_row("SELECT * FROM " . SH_WEB_STATS_TRACKING . " WHERE id='" . $lastid . "'", ARRAY_A);

            $page_post_type=get_post_type();

            if ( $page_post_type!=''){
            $wpdb->query("INSERT INTO " . SH_WEB_STATS_DETAILS . "(p_id,p_type,p_title,tracking_id,capture_date,`visitorinformations` , `os_type` , `os_family` , `os_name` ,  `os_version` ,  `os_title` ,  `device_type` ,  `browser_name` ,  `browser_version`,  `browser_title`  ,  `browser_chrome_original`,  `browser_firefox_original`,  `browser_safari_original`,  `browser_chromium_version`,  `browser_gecko_version`,  `browser_webkit_version`,  `browser_android_webview`,  `browser_ios_webview`,  `browser_desktop_mode`,  `64bits_mode`) VALUES ('" .  $post->ID . "','".$page_post_type."','" . esc_sql($post->post_title) . "','".$uuidetails['tracking_id']."','".current_time('mysql')."','".serialize( $result)."','". esc_sql($result['os_type'])."','". esc_sql($result['os_family'])."','". esc_sql($result['os_name'])."','". esc_sql($result['os_version'])."','". esc_sql($result['os_title'])."','". esc_sql($result['device_type'])."','". esc_sql($result['browser_name'])."','". esc_sql($result['browser_version'])."','". esc_sql($result['browser_title'])."','". ($result['browser_chrome_original'])."','". ($result['browser_firefox_original'])."','". ($result['browser_safari_original'])."','". ($result['browser_chromium_version'])."','". ($result['browser_gecko_version'])."','". ($result['browser_webkit_version'])."','". ($result['browser_android_webview'])."','". ($result['browser_ios_webview'])."','". ($result['browser_desktop_mode'])."','". ($result['64bits_mode'])."')");
            }

         }
       

        }
	
}
?>