<?php
// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Loads function that then returns the required capabilities for two main
// roles. form_user and form_admin
require_once( plugin_dir_path(__FILE__) . 'user-roles-and-capabilities.php' );


function bat_register_new_user_roles() {

    $user_roles_capabilities = bat_return_user_roles_capabilities();

    bat_register_question_admin_role( $user_roles_capabilities );
    bat_register_form_user_role( $user_roles_capabilities );
    bat_add_capabilities_to_admin( $user_roles_capabilities );

}


function bat_register_question_admin_role( $user_roles_capabilities ) {

    $form_admin_role_name = 'form_admin';
    $form_admin_role_display_name = 'Form Admin';

    $editor_role = get_role('editor');
    $editor_capabilities = $editor_role->capabilities;

    add_role(
        $form_admin_role_name,
        $form_admin_role_display_name,
        $editor_capabilities
    );

    $form_admin_role = get_role( $form_admin_role_name );
    $form_admin_additional_capabilities = $user_roles_capabilities['form_admin_capabilities'];

    foreach ($form_admin_additional_capabilities as $capability) {

        $form_admin_role->add_cap( $capability, true );

    }

}


function bat_register_form_user_role( $user_roles_capabilities ) {

    $form_user_role_name = 'form_user';
    $form_user_role_display_name = 'Form User';

    $author_role = get_role('author');
    $author_role_capabilities = $author_role->capabilities;

    add_role(
        $form_user_role_name,
        $form_user_role_display_name,
        $author_role_capabilities
    );

    $form_user_role = get_role( $form_user_role_name );
    $form_user_role_extra_capabilitites = $user_roles_capabilities['form_user_capabilities'];

    foreach ($form_user_role_extra_capabilitites as $capability) {

        $form_user_role->add_cap( $capability, true );
        
    }

}


function bat_add_capabilities_to_admin( $user_roles_capabilities ){

    $admin_role = get_role( 'administrator' );

    $admin_extra_capabilities = $user_roles_capabilities['form_admin_capabilities'];
    foreach( $admin_extra_capabilities as $capability) {

        $admin_role->add_cap( $capability, true );

    }
}

