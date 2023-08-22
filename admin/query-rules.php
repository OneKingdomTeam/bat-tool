<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('pre_get_posts', 'thet_query_rules' );

function thet_query_rules( $query ){

    global $post;
    var_dump( is_user_logged_in() );
    if ( $post !== NULL && $post->post_type === 'applications' && is_user_logged_in() ) {

        $is_user_admin = in_array( 'administrator' ,  wp_get_current_user()->roles );
        $is_user_form_admin = in_array( 'form_admin' ,  wp_get_current_user()->roles);

        var_dump( array( $is_user_admin, $is_user_form_admin ));

        if ( $is_user_admin === false && $is_user_form_admin === false ) {

            $post_author_id = intval( $post->post_author );
            $current_user_id = get_current_user_id();
            $user_is_author = $post_author_id === $current_user_id;
            var_dump( $user_is_author );
            if ( !$user_is_author ){

                if ( defined('REST_REQUEST') ){
                
                    $response = array( 'response' => 'Unauthorised request');
                    $status_code = 401;
                    wp_send_json( $response, $status_code );

                } else {

                    wp_die (
                    
                        "You cannot view this content",
                        "Unauthorised",
                        array(

                            'response' => 401
                        
                        )
                
                    );

                }

            }

        }     

    }

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
