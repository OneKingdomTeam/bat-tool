<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function thet_activation(){

    require_once plugin_dir_path( __FILE__ ) . 'de-register-user-roles.php';
    require_once plugin_dir_path( __FILE__ ) . 'manage-posts.php';
    require_once plugin_dir_path( __FILE__ ) . 'homepage-ctl.php';
    require_once plugin_dir_path( __FILE__ ) . '../admin-notes/create-table.php';


    thet_register_new_user_roles();
    thet_create_pages();
    thet_set_application_page_as_homepage();

    // Needs to run after questions are already created to correctly map 
    // note_id to question_id
    thet_create_admin_notes_tables();

}


function thet_deactivation(){

    require_once plugin_dir_path( __FILE__ ) . 'de-register-user-roles.php';
    require_once plugin_dir_path( __FILE__ ) . 'manage-posts.php';
    require_once plugin_dir_path( __FILE__ ) . 'homepage-ctl.php';
    
    thet_remove_new_user_roles();
    thet_remove_pages();
    thet_reset_homepage_settings();
    delete_option( 'thet_options' );

}
