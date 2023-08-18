<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('pre_get_posts', 'thet_query_rules' );

function thet_query_rules( $query ){

    $query_post_type = $query->get('post_type');
    global $current_user;

    $rest_request = false;
    if ( defined( 'REST_REQUEST' ) ){
        $rest_request = REST_REQUEST;
    }

    
    if ($query_post_type == 'applications' && $rest_request && $current_user->ID == 0 ){

        $response = array( 'response' => 'Unauthorised request');
        $status_code = 401;
        wp_send_json( $response, $status_code );

    }

    
    if ($query_post_type == 'applications' && ! current_user_can( 'edit_others_questions' )){

        $query->set( 'author', $current_user->ID );

    }

}
