<?php
// protection
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function sh_web_stats_visitor_summary_informations_func()
{
    global $wpdb;
    echo '<div class="sh_stats_box">';
    echo '<div class="sh_stats_title">Visitors Summary</div>';
    echo '<div class="sh_information_box">';
   
    echo '<table width="90%" align="center" class="widefat table-stats sh-visitor-summary-stats">';
    echo '<tr><th width="60%"></th><th class="th-center">Visitors</th><th class="th-center">Visits</th></tr>';

    $todayvisit = $wpdb->get_row("SELECT COUNT(*) AS CNTTODAY FROM " . SH_WEB_STATS_TRACKING . " WHERE `tracking_id`!='' AND DATE_FORMAT(`capture_date`,'%Y-%m-%d')=CURDATE() ORDER BY `capture_date` DESC", ARRAY_A);

    $todayvisitors = $wpdb->get_row("SELECT COUNT(*) AS CNTTODAY FROM " . SH_WEB_STATS_DETAILS . " WHERE `tracking_id`!='' AND DATE_FORMAT(`capture_date`,'%Y-%m-%d')=CURDATE() ORDER BY `capture_date` DESC", ARRAY_A);

     echo '<tr><th>Today: </th><th class="th-center">'.$todayvisit['CNTTODAY'].'</th><th class="th-center">'.$todayvisitors['CNTTODAY'].'</th></tr>';


     $sevendayvisit = $wpdb->get_row("SELECT COUNT(*) AS CNTSEVENDAY FROM " . SH_WEB_STATS_TRACKING . " WHERE `tracking_id`!='' AND `capture_date` BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() ORDER BY `capture_date` DESC", ARRAY_A);

     $sevendayvisitors = $wpdb->get_row("SELECT COUNT(*) AS CNTSEVENDAY FROM " . SH_WEB_STATS_DETAILS . " WHERE `tracking_id`!='' AND `capture_date` BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() ORDER BY `capture_date` DESC", ARRAY_A);
 
     

      echo '<tr><th>Last 7 day: </th><th class="th-center">'.$sevendayvisit['CNTSEVENDAY'].'</th><th class="th-center">'.$sevendayvisitors['CNTSEVENDAY'].'</th></tr>';


      $thirtydayvisit = $wpdb->get_row("SELECT COUNT(*) AS CNTTHIRTYDAY FROM " . SH_WEB_STATS_TRACKING . " WHERE `tracking_id`!='' AND `capture_date` BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ORDER BY `capture_date` DESC", ARRAY_A);

      $thirtydayvisitors = $wpdb->get_row("SELECT COUNT(*) AS CNTTHIRTYDAY FROM " . SH_WEB_STATS_DETAILS . " WHERE `tracking_id`!='' AND `capture_date` BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ORDER BY `capture_date` DESC", ARRAY_A);

      echo '<tr><th>Last 30 day: </th><th class="th-center">'.$thirtydayvisit['CNTTHIRTYDAY'].'</th><th class="th-center">'.$thirtydayvisitors['CNTTHIRTYDAY'].'</th></tr>';


     echo '</table>';
    echo '</div>';
    echo '</div>';
    
}

function sh_web_stats_server_informations_func(){
    echo '<div class="sh_stats_box">';
    echo '<div class="sh_stats_title">Server Health Informations</div>';
    echo '<div class="sh_information_box">
            <p>System: <strong>'.php_uname('s').'</strong></p>
            <p>PHP Version: <strong>'.phpversion().'</strong></p>
            <p>Memory Limit: <strong>'.ini_get('memory_limit').'</strong></p>
            <p>Max Execution Time: <strong>'.ini_get('max_execution_time').' Seconds</strong></p>
            <p>Upload Max Filesize: <strong>'.ini_get('upload_max_filesize').'</strong></p>
         </div>';
    echo '</div>';
}
function sh_web_stats_products_informations_func(){
    echo '<div class="sh_stats_box">';
    echo '<div class="sh_stats_title">Woocommerce Product Informations</div>';
    echo '<div class="sh_information_box">
            <p>Product Counter: '.count(get_posts([
                'posts_per_page' => -1,
                'depth'          => -1,
                  'post_status' => 'any',
                  'post_type' => 'product'
            ])).'</p>
            <p>Product Categories: '.count(get_categories([
                'taxonomy'     => 'product_cat',
                'orderby'      => 'name',
                'show_count'   => 0,
                'pad_counts'   => 0,
                'hierarchical' => 1,
                'title_li'     => '',
                'hide_empty'   => 0
            ])).'</p>
<p>Product Tags: '.count(get_categories([
                'taxonomy'     => 'product_tag',
                'orderby'      => 'name',
                'show_count'   => 0,
                'pad_counts'   => 0,
                'hierarchical' => 1,
                'title_li'     => '',
                'hide_empty'   => 0
            ])).'</p>
         </div>';
    echo '</div>';
}
function sh_web_stats_pages_informations_func()
{
    echo '<div class="sh_stats_box">';
    echo '<div class="sh_stats_title">Blog Posts & Pages Informations</div>';
    echo '<div class="sh_information_box">
            <p>All Blog Posts count: <strong>'.count(get_posts([
                'posts_per_page' => -1,
                'depth'          => -1,
                  'post_status' => 'any',
                  'post_type'   => 'post',
            ])).'</strong></p>
            <p>Published Blog Posts count: <strong>'.count(get_posts([
                'posts_per_page' => -1,
                'depth'          => -1,
                  'post_status' => 'publish',
                  'post_type'   => 'post',
            ])).'</strong></p>
            <p>Comments count: <strong>'.get_comments_number().'</strong></p>
            <p> All Pages Count: <strong>'.count(get_posts([
                'posts_per_page' => -1,
                'depth'          => -1,
                'post_status' => 'any',
                  'post_type' => 'page'
            ])).'</strong></p>
            <p> Published Pages Count: <strong>'.count(get_posts([
                'posts_per_page' => -1,
                'depth'          => -1,
                'post_status' => 'publish',
                  'post_type' => 'page'
            ])).'</strong></p>
         </div>';
    echo '</div>';
}


function sh_web_stats_server_extended_informations_func()
{

    echo '<div class="sh_stats_box">';
    echo '<div class="sh_stats_title">Server Extended Informations</div>';
    echo '<div class="sh_information_box">
            <p>Server Software: <strong>'.$_SERVER['SERVER_SOFTWARE'].'</strong></p>
            <p>Server IP: <strong>'.((!filter_var(trim(gethostbyname(gethostname())), FILTER_VALIDATE_IP))?trim(gethostbyname(gethostname())):$_SERVER['SERVER_ADDR']).'</strong></p>
            <p>Server Port: <strong>'.$_SERVER['SERVER_PORT'].'</strong></p>
            <p>Server Hostname: <strong>'.gethostname().'</strong></p>
            <p>Site\'s Document Root: <strong>'.$_SERVER['DOCUMENT_ROOT'] . '/'.'</strong></p>
            <p>Memcached Enabled: <strong>'.(class_exists('Memcache') ?'Yes':'No') . '</strong></p>
            <p>CPU Load: <strong id="cpuload"></strong></p>
            <p>Memory Used: <strong id="memused"></strong></p>
            
         </div>';
    echo '</div>';
}
?>