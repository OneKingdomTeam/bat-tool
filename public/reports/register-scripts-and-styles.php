<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function bat_reports_enqueue_public_scripts_and_styles(){

    $current_post = get_post();
    
    if( $current_post && $current_post->post_type === 'report' ){

        wp_register_style('bat_reports_styles', bat_get_env()['root_dir_url'] . 'public/css/reports.css', [], false);
        wp_enqueue_style('bat_reports_styles');

    
    }

}

add_action('wp_enqueue_scripts', 'bat_reports_enqueue_public_scripts_and_styles');
