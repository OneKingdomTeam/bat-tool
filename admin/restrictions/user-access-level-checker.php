<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Noticed that across the site I am checking whether or not user
// can or can not access specific resources. It will be better
// to have stand-alone function that takes parameters and returns
// well readable statements that the requiring function can understand.

function thet_can_user_access (int $post_id, string $nonce, string $session_key, string $intent):bool {

    $requested_post = get_post($post_id);

    // Verify if the provided nonce is valid
    $nonce_is_valid = false;
    if ( wp_verify_nonce( $nonce, 'thet_ajax' ) ){
        $nonce_is_valid = true;
    } else {
        return false;
    }


    // Checks whether the user has access either by owning the resources
    // or by being administrator either form-admin or administrator himself
    $user_can_access = false;    
    if ( is_user_logged_in() ){

        $requesting_user = wp_get_current_user();

        if ( in_array( 'form_admin', $requesting_user->roles )
           || in_array( 'administrator', $requesting_user->roles )
           || $requested_post->post_author === $requesting_user->ID )
        {
            $user_can_access = true;    
        } else {
            return false;
        }
    } else {
        return false;
    }

    // checks if intent is to read or write data
    $session_key_verified = false;
    if ( $intent == 'read' ) {
        $session_key_verified = true;
    } 

    if ( $intent == 'write' ){

        // VERIFY the SESSION KEY BEFORE WRITTING
        // DONT FORGET TO CHECK IN THE TIME

    }

    if ( $nonce_is_valid && $user_can_access && $session_key_verified ) {
        return true;
    } else {
        return false;
    }
    
}
