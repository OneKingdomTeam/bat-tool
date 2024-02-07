<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function thet_enqueue_admin_notes_fornt_end_js_and_css() {


    if ( !thet_check_is_user_logged_in_and_admin() &&
         !thet_check_is_post_interactive_form_page() ) {

        return;
        
    }
    
    // If user is logged in, is administrator or form_admin and post ID is interactive form page ID loades the codes
    wp_register_style('admin_notes_frontend_css', plugin_dir_url(__FILE__) . 'css/admin-notes.css', [], false);
    wp_register_script('admin_notes_frontend_script', plugin_dir_url(__FILE__) . 'js/admin-notes.js', [], false, true);

    wp_localize_script('admin_notes_frontend_script', 'adminNotesLoc', [
        'nonce' => thet_admin_notes_create_frontend_nonce(),
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ]);

    wp_enqueue_style('admin_notes_frontend_css');
    wp_enqueue_script('admin_notes_frontend_script');

}

add_action('wp_enqueue_scripts', 'thet_enqueue_admin_notes_fornt_end_js_and_css');
