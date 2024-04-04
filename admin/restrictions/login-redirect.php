<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Redirect non-logged in users to the login page. Page is basically only
// for logged in users noone else has anything to do here.

add_action( 'template_redirect', 'thet_redirect_unauthenticated_users' );

function thet_redirect_unauthenticated_users(){

    global $post;

    if ( !empty($post) && $post->post_type === 'reports' ){
        return;
    }

    if ( !is_user_logged_in() ){
    // Page generally should not be used by users that aren't logged in.
        auth_redirect();
    }

}
