<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Restrict access to the WP Admin for NON logged in users
// Maybe there should be the check for regular users as well.

add_action( 'init', 'bat_restrict_wp_admin');


function bat_restrict_wp_admin(){

    // Editting questions is pretty good way to check if user
    // has elevated privileges. Since those should rarely be changed
    // by only few priviliged users/mentors
    if ( !current_user_can( 'edit_others_questions' )) {

        // Hides it for regular users
        show_admin_bar( false );
        
        if ( is_admin() && !wp_doing_ajax() ){

            wp_redirect( '/' );
            exit;

        }

    }

}
