function sh_web_stats_memory_usage() {
    var data = {
        'action': 'sh_web_stats_memory_usage'
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajax_object.ajax_url, data, function(response) {
        response = JSON.parse(response);

        jQuery('#memused').html(response.memused);
        jQuery('#cpuload').html(response.cpuload);



    });
}