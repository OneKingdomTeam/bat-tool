<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Sets whether or not the current user will see others applications
// based on the fact whether or not user can edit_others_applications
// otherwise modifies the query to show only the user OWNED applications

add_action('pre_get_posts', 'bat_application_query_rules' );

function bat_application_query_rules( $query ){

    $query_post_type = $query->get('post_type');

    global $current_user;

    if ($query_post_type == 'applications' && ! current_user_can( 'edit_others_applications' )){
        $query->set( 'author', $current_user->ID );
    }

}

