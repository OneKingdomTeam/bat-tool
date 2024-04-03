<?php
// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function bat_tool_activation(){

    require_once bat_get_env()['root_dir_path'] . 'includes/user-roles-and-caps/register-user-roles-and-caps.php';
    require_once bat_get_env()['root_dir_path'] . 'includes/template-adjustments/create-pages.php';
    require_once bat_get_env()['root_dir_path'] . 'includes/questions/create-questions.php';
    require_once bat_get_env()['root_dir_path'] . 'includes/template-adjustments/set-homepage.php';

    require_once bat_get_env()['root_dir_path'] . 'includes/admin-notes/create-tables.php';


    bat_register_new_user_roles();
    bat_create_pages();

    // Checks whether the questions already exits, since we don't want
    // to overwrite them for cases of people changing them during the
    // lifetime of the plugin installation
    if ( empty( get_posts( [ 'post_type' => 'questions' ] ) ) ){
        bat_create_questions();   
    }

    bat_set_application_page_as_homepage();


    // Needs to run after questions are already created to correctly map 
    // note_id to question_id
    bat_create_admin_notes_tables();

}


function bat_tool_deactivation(){

    require_once plugin_dir_path( __FILE__ ) . 'user-roles-and-caps/deregister-user-roles-and-caps.php';
    require_once plugin_dir_path( __FILE__ ) . 'template-adjustments/remove-pages.php';
    require_once plugin_dir_path( __FILE__ ) . 'template-adjustments/reset-homepage.php';
    
    bat_remove_new_user_roles();
    bat_remove_pages();
    bat_reset_homepage_settings();

    delete_option( 'bat_options' );

}

