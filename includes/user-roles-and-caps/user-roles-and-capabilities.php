<?php
// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function bat_return_user_roles_capabilities(){

    $user_roles_capabilities = [

        'form_admin_capabilities' => [

                // The questions should be only created once during the first
                // plugin activation since their ID is critical for keeping track
                // of teams answers as well as mentor notes.
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

                'edit_report', 
                'read_report', 
                'delete_report', 
                'edit_reports', 
                'edit_others_reports', 
                'publish_reports',
                'read_private_reports', 
                'edit_reports', 
            ],

        'form_user_capabilities' => [
                'edit_application', 
                'read_application',
                'edit_applications'
            ]
    ];
    
    return $user_roles_capabilities;

}


