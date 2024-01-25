<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function thet_enqueue_admin_notes_fornt_end_js_and_css() {

    

    wp_register_style('admin_notes_frontend_css', plugin_dir_url(__FILE__) . 'css/admin-notes-frontend.css', [], false);
    wp_register_script('admin_notes_frontend_script', plugin_dir_url(__FILE__) . 'js/admin-notes-frontend.js', [], false, true);

    wp_localize_script('admin_notes_frontend_script', 'adminNotesNonce', [
        'nonce' => thet_admin_notes_create_frontend_nonce(),
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ]);

    wp_enqueue_style('admin_notes_frontend_css');
    wp_enqueue_script('admin_notes_frontend_script');

}
