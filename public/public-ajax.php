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

    $new_session_key = sanitize_title( $_POST['session_key'] );

    $required_application_id = intval( $_POST['application_id'] );
    $required_application_object = get_post( $required_application_id );

    $application_author_id = $required_application_object->post_author;
    $requesting_user = wp_get_current_user();

    // Various checking variables to prevent opening 2 instances
    $last_save_time = intval( get_post_meta( $required_application_id, 'last_save_time', true ) );
    $time_now = time();
    $since_last_save = $time_now - $last_save_time;
    $last_editor = get_post_meta( $required_application_id, '_edit_last', true );
    $force_opening = boolval( $_POST['force_open'] );

    // Verify if user can actually access the application
    if ( intval( $requesting_user->ID ) === intval( $application_author_id ) ){
        $user_can_access = true;
    }
    if ( in_array( 'form_admin', $requesting_user->roles ) || in_array( 'administrator', $requesting_user->roles )){
        $user_can_access = true;
    }


    // If all goes well sends data to frontend
    if ( $user_is_logged_in && $nonce_is_valid !== false && $user_can_access ){
    
        if ( $since_last_save >= 30 || $force_opening === true ){

            update_post_meta( $required_application_id, '_edit_last', $requesting_user->ID );   
            update_post_meta( $required_application_id, 'last_editor_session_key', $new_session_key );
            update_post_meta( $required_application_id, 'last_save_time', time() );
            $required_application_data = get_post_meta( $required_application_id, 'answers_data', true );
            wp_send_json( $required_application_data );
            wp_die();

        } else {
    
            if ( $since_last_save < 30 && intval( $requesting_user->ID ) !== intval( get_post_meta( $required_application_id, '_edit_last', true ) ) ) {

                $last_editor_name = get_userdata( intval( $last_editor ) )->user_login;

            
                $response = [
                    'response' => 'Warning',
                    'message' => 'Last save is less than 30 seconds ago. Seems that user: "' . $last_editor_name . '" is currently editting or viewing this application. Try again later.',
                ];

                wp_send_json( $response, 401 );
                wp_die();


            }

            if ( $since_last_save < 30 && intval( $requesting_user->ID ) === intval( get_post_meta( $required_application_id, '_edit_last', true ) ) ) {
            
                $response = [
                    'response' => 'Warning',
                    'message' => 'Seems that this application is opened by you in another window. It may also happened that you closed the application and reopend it within 30 seconds. Check other browser tab or reload this page after at least 30 seconds.',
                ];

                wp_send_json( $response, 401 );
                wp_die();
            }

        
        }

    } 

    wp_send_json( 
        [ 'response' => 'unauthorised' ], 401 );
    wp_die();

};

add_action( 'wp_ajax_save_application_data', 'thet_save_application_data');

function thet_save_application_data(){

    $data = json_decode( stripslashes( $_POST['data'] ));
    
    $nonce_is_valid = wp_verify_nonce( $_POST['nonce'], 'thet_ajax' );
    $user_is_logged_in = is_user_logged_in();
    $new_data_exists = isset( $_POST['data'] );
    $user_can_access = false;

    $current_session_key = sanitize_title( $_POST['session_key'] );

    $required_application_id = intval( $_POST['application_id'] );
    $required_application_object = get_post( $required_application_id );

    $application_author_id = $required_application_object->post_author;
    $requesting_user = wp_get_current_user();

    $last_editor = get_post_meta( $required_application_id, '_edit_last', true );

    if ( intval( $requesting_user->ID ) === intval( $application_author_id ) ){
        $user_can_access = true;
    }
    if ( in_array( 'form_admin', $requesting_user->roles ) || in_array( 'administrator', $requesting_user->roles )){
        $user_can_access = true;
    }
    
    if ( $user_is_logged_in && $nonce_is_valid !== false && $new_data_exists && $user_can_access ){
        
        if ( intval( $requesting_user->ID ) !== intval( $last_editor ) ){

            $last_editor_name = get_userdata( intval( $last_editor ) )->user_login;

            $response = [
                'response' => 'Overtaken',
                'message' => 'User: "' . $last_editor_name . '" took over the form. Sorry about that, you can go back to application listing, or trying to reload the page in a bit.'
            ];
            
            wp_send_json( $response, 401 );
            wp_die();

        }

        if ( $current_session_key !== get_post_meta( $required_application_id, 'last_editor_session_key', true  ) ){

            $response = [
                'response' => 'Overtaken',
                'message' => 'Seems that you opened the form in the new window. Try finding the window or hit "Force open" to open take over in this window.'
            ];

            wp_send_json( $response, 401 );
            wp_die();

        }

        update_post_meta( $required_application_id, '_edit_last', $requesting_user->ID );   
        update_post_meta( $required_application_id, 'last_editor_session_key', $current_session_key );
        update_post_meta( $required_application_id, 'answers_data', $data );
        update_post_meta( $required_application_id, 'last_save_time', time() );
        
        $response = [
            'response' => 'Okay',
            'last_save_time' => get_post_meta( $required_application_id, 'last_save_time', true ),
            'last_editor' => get_post_meta( $required_application_id, '_edit_last', true ),
            'last_editor_session_key' => get_post_meta( $required_application_id, 'last_editor_session_key', true ),
        ];

        wp_send_json( $response );
        wp_die();

    }
    
    wp_send_json( 'unauthorised', 401);
    wp_die();

}


add_action( 'wp_ajax_get_recent_update_time', 'thet_get_recent_update_time');

function thet_get_recent_update_time(){

    $applications = get_posts( ['post_type' => 'applications' ] );
    
    $output = [];

    foreach( $applications as $application ){
        $output[ $application->ID ]['title'] = $application->post_title;
        $output[ $application->ID ]['last_save_time'] = get_post_meta( $application->ID, 'last_save_time', true );
    }

    wp_send_json( $output );
    wp_die();

}
