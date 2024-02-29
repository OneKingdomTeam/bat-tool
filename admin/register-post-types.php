<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('init', 'thet_register_new_post_types');

function thet_register_new_post_types(){

    thet_register_applications();
    thet_register_questions();
    thet_register_reports();

}

// #####################################################################################
// #####################################################################################

function thet_register_applications() {

    $labels = [

        'name'          => _x( 'Applications', 'Post type general name', 'textdomain'),
        'singular_name' => _x( 'Application', 'Post type singular name', 'textdomain'),
        'menu_name' => _x( 'Applications', 'Admin menu text', 'textdomain'),
        'add_new' => _x( 'Add new', 'Add New on Toolbar', 'textdomain')

    ];

    $args = [

        'labels'              => $labels,
        'description'         => 'Form applications of the teams.',
        'public'              => false,
        'exclude_from_search' => true,
        'show_ui' => true,
        'capabilities' => [
            'edit_post'          => 'edit_application', 
            'read_post'          => 'read_application', 
            'delete_post'        => 'delete_application', 
            'edit_posts'         => 'edit_applications', 
            'edit_others_posts'  => 'edit_others_applications', 
            'publish_posts'      => 'publish_applications',       
            'read_private_posts' => 'read_private_applications', 
            'create_posts'       => 'edit_applications', 
        ],
        'show_in_rest' => false,
        'menu_icon' => 'dashicons-format-aside',
        'supports' => [ 'title', 'author' ],

    ];


    register_post_type( 'applications', $args );

}

// #####################################################################################

function thet_register_questions(){

    $labels = [

        'name'          => _x( 'Questions', 'Post type general name', 'textdomain'),
        'singular_name' => _x( 'question', 'Post type singular name', 'textdomain'),
        'menu_name' => _x( 'Questions', 'Admin menu text', 'textdomain'),
        'add_new' => _x( 'Add new', 'Add New on Toolbar', 'textdomain')

    ];

    $args = [

        'labels'   => $labels,
        'description' => 'Question that teams will asnwer',
        'public' => false,
        'exclude_from_search' => true,
        'show_ui' => true,
        'capabilities' => [
            'edit_post'          => 'edit_question', 
            'read_post'          => 'read_question', 
            'delete_post'        => 'delete_question', 
            'edit_posts'         => 'edit_questions', 
            'edit_others_posts'  => 'edit_others_questions', 
            'publish_posts'      => 'publish_questions',       
            'read_private_posts' => 'read_private_questions', 
            'create_posts'       => 'edit_questions', 
        ],
        'show_in_rest' => false,
        'menu_icon' => 'dashicons-format-quote',
        'supports' => [ 'title', 'author' ],

    ];

    register_post_type( 'questions', $args );

}

function thet_register_reports(){

    $labels = [

        'name'          => _x( 'Reports', 'Post type general name', 'textdomain'),
        'singular_name' => _x( 'report', 'Post type singular name', 'textdomain'),
        'menu_name' => _x( 'Reports', 'Admin menu text', 'textdomain'),
        'add_new' => _x( 'Create report', 'Add New on Toolbar', 'textdomain')

    ];

    $args = [

        'labels'   => $labels,
        'description' => 'Holds reports created for the teams.',
        'public' => false,
        'exclude_from_search' => true,
        'show_ui' => true,
        'capabilities' => [
            'edit_post'          => 'edit_report', 
            'read_post'          => 'read_report', 
            'delete_post'        => 'delete_report', 
            'edit_posts'         => 'edit_reports', 
            'edit_others_posts'  => 'edit_others_reports', 
            'publish_posts'      => 'publish_reports',
            'read_private_posts' => 'read_private_reports', 
            'create_posts'       => 'edit_reports', 
        ],
        'show_in_rest' => false,
        'menu_icon' => 'dashicons-clipboard',
        'supports' => [ 'title', 'author', 'revisions', 'thumbnail' ],

    ];

    register_post_type( 'reports' , $args );

}
