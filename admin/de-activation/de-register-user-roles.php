<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function thet_return_user_roles_capabilities(){

    $user_roles_capabilities = array(

        'form_admin_capabilities' => array(
                'edit_question', 
                'read_question', 
                //'delete_question', 
                'edit_questions', 
                'edit_others_questions', 
                //'publish_questions',       
                'read_private_questions', 
                'edit_questions',
                'edit_application', 
                'read_application', 
                'delete_application', 
                'edit_applications', 
                'edit_others_applications', 
                'publish_applications',       
                'read_private_applications', 
                'edit_applications',
                'edit_users',
                'delete_users',
                'create_users',
                'list_users',
                'remove_users',
                'promote_users',
            ),
        'form_user_capabilities' => array(
                'edit_application', 
                'read_application',
                'edit_applications'
            )
    );
    
    return $user_roles_capabilities;

}

function thet_register_new_user_roles() {

    $user_roles_capabilities = thet_return_user_roles_capabilities();

    thet_register_question_admin_role( $user_roles_capabilities );
    thet_register_form_user_role( $user_roles_capabilities );
    thet_add_capabilities_to_admin( $user_roles_capabilities );

}

function thet_remove_new_user_roles() {

    $user_roles_capabilities = thet_return_user_roles_capabilities();

    thet_remove_question_admin_role();
    thet_remove_form_user_role();
    thet_remove_capabilities_from_admin( $user_roles_capabilities );

}

// ############################################################################################

function thet_add_capabilities_to_admin( $user_roles_capabilities ){

    $admin_role = get_role( 'administrator' );

    $admin_extra_capabilities = $user_roles_capabilities['form_admin_capabilities'];
    foreach( $admin_extra_capabilities as $capability) {

        $admin_role->add_cap( $capability, true );

    }
}

function thet_register_question_admin_role( $user_roles_capabilities ) {

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


function thet_register_form_user_role( $user_roles_capabilities ) {

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


function thet_remove_question_admin_role(){

    remove_role('question_admin');

}


function thet_remove_form_user_role(){

    remove_role('form_user');

}


function thet_remove_capabilities_from_admin( $user_roles_capabilities ){

    $admin_role = get_role( 'administrator' );

    $admin_extra_capabilities = $user_roles_capabilities['form_admin_capabilities'];
    foreach( $admin_extra_capabilities as $capability) {

        $admin_role->remove_cap( $capability );

    }

}
