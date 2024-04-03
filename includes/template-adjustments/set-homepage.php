<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function bat_set_application_page_as_homepage(){

    $bat_options = get_option( 'bat_options' );

    update_option('page_on_front', $bat_options['applications_page_id'] );
    update_option('show_on_front', 'page');
    
}


