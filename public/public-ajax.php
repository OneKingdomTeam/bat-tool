<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_ajax_get_application_data', 'thet_get_application_data');

function thet_get_application_data(){

    $nonce_is_valid = wp_verify_nonce( $_POST['nonce'], 'thet_ajax' );
    $user_is_logged_in = is_user_logged_in();
    $user_can_access = false;

    $required_application_id = intval( $_POST['application_id'] );
    $required_application_object = get_post( $required_application_id );

    $application_author_id = $required_application_object->post_author;
    $requesting_user = wp_get_current_user();


    if ( intval( $requesting_user->ID ) === intval( $application_author_id ) ){
        $user_can_access = true;
    }
    if ( in_array( 'form_admin', $requesting_user->roles ) || in_array( 'administrator', $requesting_user->roles )){
        $user_can_access = true;
    }
    
    if ( $user_is_logged_in && $nonce_is_valid !== false && $user_can_access ){
        
        $required_application_data = get_post_meta( $required_application_id, 'answers_data', true );
        wp_send_json( $required_application_data );
        wp_die();

    }

    $test = [
        'req_post_author' => $required_application_object->post_author,
        'current_user_id' => $requesting_user->ID,
        'current_user_roles' => $requesting_user->roles,
        'user_can_access' => $user_can_access,
        'user_logged_in' => $user_is_logged_in,
        'nonce_is_valid' => $nonce_is_valid,
    ];

    wp_send_json( $test );
    //wp_send_json( 'Unauthorised' );
    wp_die();

};

add_action( 'wp_ajax_save_application_data', 'thet_save_application_data');

function thet_save_application_data(){

    $data = json_decode( stripslashes( $_POST['data'] ));
    
    $nonce_is_valid = wp_verify_nonce( $_POST['nonce'], 'thet_ajax' );
    $user_is_logged_in = is_user_logged_in();
    $new_data_exists = isset( $_POST['data'] );
    $user_can_access = false;

    $required_application_id = intval( $_POST['application_id'] );
    $required_application_object = get_post( $required_application_id );

    $application_author_id = $required_application_object->post_author;
    $requesting_user = wp_get_current_user();


    if ( intval( $requesting_user->ID ) === intval( $application_author_id ) ){
        $user_can_access = true;
    }
    if ( in_array( 'form_admin', $requesting_user->roles ) || in_array( 'administrator', $requesting_user->roles )){
        $user_can_access = true;
    }
    
    if ( $user_is_logged_in && $nonce_is_valid !== false && $new_data_exists && $user_can_access ){
        
        // WARNING the $_POST['data'] should be sanitized in one way or another
    
        update_post_meta( $required_application_id, 'answers_data', $data );
        update_post_meta( $required_application_id, 'last_save_time', time() );
        wp_send_json( 'okay' );
        wp_die();

    }
    
    wp_send_json( 'Unauthorised' );
    wp_die();

}
