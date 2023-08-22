<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function thet_activation(){

    require_once plugin_dir_path( __FILE__ ) . 'de-register-user-roles.php';
    require_once plugin_dir_path( __FILE__ ) . 'manage-pages.php';

    thet_register_new_user_roles();
    thet_create_pages();

}


function thet_deactivation(){

    require_once plugin_dir_path( __FILE__ ) . 'de-register-user-roles.php';
    require_once plugin_dir_path( __FILE__ ) . 'manage-pages.php';
    
    thet_remove_new_user_roles();
    thet_remove_pages();
    delete_option( 'thet_options' );

}
