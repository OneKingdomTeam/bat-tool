<?php 

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function bat_save_application_changes(){

    if ( isset($_POST) && isset($_POST['_wp_http_referer']) &&
    str_starts_with( $_POST['_wp_http_referer'], '/wp-admin/post-new.php') &&
    $_POST['post_type'] === 'applications' ){

        update_post_meta( $_POST['ID'] , 'last_save_time', time() );

    }
}

add_action('save_post_applications', 'bat_save_application_changes');
