<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Redirect non-logged in users to the login page. Page is basically only
// for logged in users noone else has anything to do here.

add_action( 'template_redirect', 'bat_redirect_unauthenticated_users' );

function bat_redirect_unauthenticated_users(){

    global $post;

    // We need to allow reports to be loaded even for unauthenticated users
    // since those are/should be protected by password straight on the post itself
    if ( !empty($post) && $post->post_type === 'report' ){
        return;
    }

    // otherwise we redirect user to log in. You shoudn't be here unless you have
    // account on this web.
    if ( !is_user_logged_in() ){
        auth_redirect();
    }

}

