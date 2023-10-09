<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('pre_get_posts', 'thet_query_rules' );

function thet_query_rules( $query ){

    $query_post_type = $query->get('post_type');
    global $current_user;

    if ($query_post_type == 'applications' && ! current_user_can( 'edit_others_applications' )){

        $query->set( 'author', $current_user->ID );

    }

}
