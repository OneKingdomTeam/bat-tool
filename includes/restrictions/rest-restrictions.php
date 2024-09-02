<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function bat_restrict_rest_api_to_logged_in_users( $result ) {
    if ( ! is_user_logged_in() ) {
        return new WP_Error(
            'rest_not_logged_in',
            __('You are not currently logged in.'),
            array( 'status' => 401 )
        );
    }
    return $result;
}

add_filter( 'rest_authentication_errors', 'bat_restrict_rest_api_to_logged_in_users' );

