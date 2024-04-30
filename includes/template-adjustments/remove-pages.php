<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function bat_remove_pages(){

    $bat_options = get_option( 'bat_options' );
    
    wp_delete_post( $bat_options['applications_page_id'] , true );
    wp_delete_post( $bat_options['interactive_form_page_id'] , true );

}
