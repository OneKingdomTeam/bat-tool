<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_filter( 'rest_authentication_errors', function( $result ) {
    // If a previous authentication check was applied,
    // pass that result along without modification.
    if ( true === $result || is_wp_error( $result ) ) {
        return $result;
    }


    $restricted_routes = array(
        '/wp-json/wp/v2/applications',
        '/wp-json/wp/v2/questions',
    );

    $current_rest_route = $GLOBALS['wp']->query_vars['rest_route'];

    $is_restricted = false;

    foreach ( $restricted_routes as $route ) {
        if ( str_contains( $current_rest_route, $route )) {
            $is_restricted = true;
        }       
    }

    // No authentication has been performed yet.
    // Return an error if user is not logged in.
    if ( $is_restricted ) {
        if ( ! is_user_logged_in() ) {
            return new WP_Error(
                'rest_not_logged_in',
                __( 'You are not currently logged in.' ),
                array( 'status' => 401 )
            );
        }
    } 

    // Our custom authentication check should have no effect
    // on logged-in requests
    return $result;
});
