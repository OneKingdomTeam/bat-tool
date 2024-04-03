<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function bat_create_pages(){
    
    // Application page
    $applications_page_attr = array(

        'post_type' => 'page',
        'post_title' => 'Applications',
        'post_status' => 'publish',
        'post_content' => file_get_contents( bat_get_env()['root_dir_path'] . 'admin/templates/applications-page.html' ),

    );
    
    $interactive_form_page_attr = array(

        'post_type' => 'page',
        'post_title' => 'Interactive form',
        'post_status' => 'publish',
        'post_content' => file_get_contents( bat_get_env()['root_dir_path'] . 'admin/templates/interactive-form.html' ),

    );


    $applications_page_id = wp_insert_post( $applications_page_attr );
    $interactive_form_page_id = wp_insert_post( $interactive_form_page_attr );

    // Stores the 2 main pages IDs as option since we need to use
    // them later for tracing back to them
    $bat_pages_ids = [
        'applications_page_id' => $applications_page_id,
        'interactive_form_page_id' => $interactive_form_page_id,
    ];


    $bat_options = get_option( 'bat_options');
    if ( $bat_options == false ){
        
        add_option( 'bat_options', $bat_pages_ids );

    }
    
}
