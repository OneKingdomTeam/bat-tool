<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function thet_check_is_user_logged_in_and_admin(): bool {

    if ( !is_user_logged_in() ){
        return false;
    }

    $current_user = wp_get_current_user();
    if( in_array('administrator', $current_user->roles) || in_array('form_admin', $current_user->roles) ){
        return true;       
    }

    return false;

}

function thet_check_is_post_interactive_form_page(): bool {

    global $post;

    if ( $post->ID === intval(get_option('thet_options')['interactive_form_page_id'] ) ) {
        return true;
    }

    return false;

}

function thet_check_is_post_applications_list_page(): bool {

    global $post;

    if ( $post->ID === intval( get_option( 'thet_options' )['applications_page_id'] ) ) {
        return true;
    }

    return false;

}
