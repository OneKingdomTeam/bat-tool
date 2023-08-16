<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function thet_register_new_user_roles() {

    thet_register_question_admin_role();
    thet_register_form_user_role();

}

function thet_remove_new_user_roles() {

    thet_remove_question_admin_role();
    thet_remove_form_user_role();

}

function thet_register_question_admin_role() {

    $role = 'form_admin';
    $display_name = 'Form Admin';

    $capabilities = get_role('editor')->capabilities;

    $manage_questions = array(
        'edit_question', 
        'read_question', 
        'delete_question', 
        'edit_questions', 
        'edit_others_questions', 
        'publish_questions',       
        'read_private_questions', 
        'edit_questions'
    );

    $manage_applications = array(
        'edit_application', 
        'read_application', 
        'delete_application', 
        'edit_applications', 
        'edit_others_applications', 
        'publish_applications',       
        'read_private_applications', 
        'edit_applications'
    );

    array_push( $capabilities, $manage_questions );
    array_push( $capabilities, $manage_applications );

    add_role(
        $role,
        $display_name,
        $capabilities
    );

}


function thet_register_form_user_role() {

    $role = 'form_user';
    $display_name = 'Form User';

    $manage_applications = array(
        'edit_application', 
        'read_application', 
        'edit_applications'
    );

    $capabilities = get_role('author')->capabilities;
    array_push( $capabilities, $manage_applications );

    add_role(
        $role,
        $display_name,
        $capabilities
    );

}


function thet_remove_question_admin_role(){

    remove_role('form_admin');

}


function thet_remove_form_user_role(){

    remove_role('form_user');

}
