<?php
// protection
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
//Overview stats
?>
<div class="sh-left sh-w-30">
    <h3><?php echo __( 'Top 10 Posts', 'sh-language' );?></h3>

    <div class="sh-table-block">
    <?php 
    
    $toptenvisitors = $wpdb->get_results("SELECT *,COUNT(`p_id`) AS CNT FROM " . SH_WEB_STATS_DETAILS . " WHERE `tracking_id`!='' AND p_type='post' GROUP BY `p_id` ORDER BY CNT DESC  LIMIT 10", ARRAY_A);


    ?>
    <table id="toptenpages" class="hover" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Link</th>
                <th>Hits</th>                
            </tr>
        </thead>
            <tbody>
                <?php
                $i=1;
                    foreach($toptenvisitors as $rcent)
                    {
                        echo '<tr>';
                        echo '<td>'.$i++.'</td>';
                        echo '<td>'.$rcent['p_title'].'</td>';
                        $post = get_post($rcent['p_id']); 
                        echo '<td><a href="'.get_permalink($rcent['p_id']).'">'.$post->post_name.'</a></td>';

                        $hitscount = $wpdb->get_row("SELECT COUNT(*) AS CNT FROM " . SH_WEB_STATS_DETAILS . " WHERE p_id ='".$rcent['p_id']."'", ARRAY_A);

                        echo '<td>'.$hitscount['CNT'].'</td>';
                        echo '</tr>';

                    }
                ?>
            <tbody>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Link</th>
                <th>Hits</th>                
            </tr>
        </tfoot>
    </table>

</div>
</div>
<div class="sh-right sh-w-70">
<h3><?php echo __( 'Recent Visitors', 'sh-language' );?></h3>

<div class="sh-table-block">
    <?php 
    
    $recentvisitors = $wpdb->get_results("SELECT * FROM " . SH_WEB_STATS_DETAILS . " WHERE `tracking_id`!='' AND p_type='post' ORDER BY `capture_date` DESC  LIMIT 10", ARRAY_A);


    ?>
    <table id="recentvisitors" class="hover" style="width:100%">
        <thead>
            <tr>
                <th>Country</th>
                <th>IP</th>
                <th>City</th>
                <th>Page</th>  
                <th>Browser</th>  
                <th>OS</th>                
            </tr>
        </thead>
            <tbody>
                <?php
                    foreach($recentvisitors as $rcent)
                    {
                        $hitdetails = $wpdb->get_row("SELECT * FROM " . SH_WEB_STATS_TRACKING . " WHERE tracking_id='".$rcent['tracking_id']."'", ARRAY_A);

                        echo '<tr>';
                        echo '<td><img src="'.plugin_dir_url(__FILE__).'flags/'.strtolower($hitdetails['country_code']).'.png" class="flag-48" /> <span>'.$hitdetails['country_name'].'</span></td>';
                        echo '<td>'.$hitdetails['visitor_ip'].'</td>';
                        echo '<td>'.$hitdetails['city'].'</td>';

                       
                        $post = get_post($rcent['p_id']); 
                        echo '<td><a href="'.get_permalink($rcent['p_id']).'">'.$post->post_name.'</a></td>';

                            $bname="";
                        if($rcent['browser_name']=='Chrome')
                        $bname="chrome";

                        if($rcent['browser_name']=='Opera')
                        $bname="opera";

                        if($rcent['browser_name']=='unknown' || $rcent['browser_name']=='' || $rcent['browser_name']==NULL)
                        $bname="browser";

                        if($rcent['browser_name']=='Safari')
                        $bname="safari";

                        if($rcent['browser_name']=='Firefox')
                        $bname="firefox";

                        if($rcent['browser_name']=='Safari Mobile')
                        $bname="safari";

                        if($rcent['browser_name']=='Yandex Browser')
                        $bname="yandex";

                        if($rcent['browser_name']=='Edge')
                        $bname="microsoft";

                        if($rcent['browser_name']=='UC Browser')
                        $bname="uc-browser";

                        echo '<td><img src="'.plugin_dir_url(__FILE__).'browsers/'.strtolower($bname).'.png" class="flag-48" /> <span>'.$rcent['browser_name'].'</span></td>';

                        echo '<td>'.$rcent['os_name'].'</td>';
                        echo '</tr>';

                    }
                ?>
            <tbody>
        <tfoot>
            <tr>
            <th>Country</th>
                <th>IP</th>
                <th>City</th>
                <th>Page</th>  
                <th>Browser</th>  
                <th>OS</th> 
            </tr>
        </tfoot>
    </table>

</div>

</div>
<div style="clear:both;"></div>

<script>
    jQuery(document).ready(function () {
        jQuery('#recentvisitors').DataTable(
            {
            "autoWidth": true,
            "paging": false
            }
        );

        jQuery('#toptenpages').DataTable(
            {
            "autoWidth": true,
            "paging": false
            }
        );
});


    </script>