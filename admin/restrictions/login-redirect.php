<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'template_redirect', 'thet_redirect_unauthenticated_users' );

function thet_redirect_unauthenticated_users(){

    if ( !is_user_logged_in() ){
    // Page generally should not be used by users that aren't logged in.

        auth_redirect();

    }

}
