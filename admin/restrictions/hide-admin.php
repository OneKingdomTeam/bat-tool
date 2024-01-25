<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// Restrict access to the WP Admin for NON logged in users
// Maybe there should be the check for regular users as well.

add_action( 'init', 'thet_restrict_wp_admin');

function thet_restrict_wp_admin(){


    if ( is_user_logged_in() ){
        $user = wp_get_current_user();

        if ( in_array( 'form_user', $user->roles ) ){

            show_admin_bar( false );

            if ( is_admin() && !wp_doing_ajax() ){
            // WP AJAX is considered as is_admin() request. So we need to exclude wp-admin/admin-ajax.php requests from it.

                wp_redirect( '/' );
                exit;

            };
    
        };

    };

}
