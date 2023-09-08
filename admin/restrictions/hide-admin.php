<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'thet_restrict_wp_admin');

function thet_restrict_wp_admin(){


    if ( is_user_logged_in() ){
        $user = wp_get_current_user();

        if ( in_array( 'form_user', $user->roles ) ){

            show_admin_bar( false );

            if ( is_admin() && !wp_doing_ajax() ){

                wp_redirect( '/' );
                exit;

            };
    
        };

    };

}
