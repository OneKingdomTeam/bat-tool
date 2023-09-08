<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function thet_set_application_page_as_homepage(){

    $thet_options = get_option( 'thet_options' );

    update_option('page_on_front', $thet_options['applications_page_id'] );
    update_option('show_on_front', 'page');
    
}


function thet_reset_homepage_settings(){

    update_option('page_on_front', 0);
    update_option('show_on_front', 'posts');

}
