<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function thet_create_pages(){
    
    // Application page
    
    $applications_page_attr = array(

        'post_type' => 'page',
        'post_title' => 'Applications',
        'post_status' => 'publish',
        'post_content' => '<!-- wp:shortcode -->[thet_get_applications]<!-- /wp:shortcode -->',

    );
    
    $interactive_form_page_attr = array(

        'post_type' => 'page',
        'post_title' => 'Interactive form',
        'post_status' => 'publish',

    );

    $applications_page_id = wp_insert_post( $applications_page_attr );
    $interactive_form_page_id = wp_insert_post( $interactive_form_page_attr );

    $thet_pages_ids = array(

        'applications_page_id' => $applications_page_id,
        'interactive_form_page_id' => $interactive_form_page_id,

    );

    $thet_options = get_option( 'thet_options');
    if ( $thet_options == false ){
        
        add_option( 'thet_options', $thet_pages_ids );

    }
    
}

function thet_remove_pages(){

    $thet_options = get_option( 'thet_options' );
    
    wp_delete_post( $thet_options['applications_page_id'] , true );
    wp_delete_post( $thet_options['interactive_form_page_id'] , true );

}
