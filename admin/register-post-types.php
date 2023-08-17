<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('init', 'thet_register_new_post_types');

function thet_register_new_post_types(){

    thet_register_applications();
    thet_register_questions();

}

function thet_register_applications() {

    $labels = array(

        'name'          => _x( 'Applications', 'Post type general name', 'textdomain'),
        'singular_name' => _x( 'Application', 'Post type singular name', 'textdomain'),
        'menu_name' => _x( 'Applications', 'Admin menu text', 'textdomain'),
        'add_new' => _x( 'Add new', 'Add New on Toolbar', 'textdomain')

    );

    $args = array(

        'labels'              => $labels,
        'description'         => 'Form applications of the teams.',
        'public'              => false,
        'exclude_from_search' => true,
        'show_ui' => true,
        'capabilities' => array(
            'edit_post'          => 'edit_application', 
            'read_post'          => 'read_application', 
            'delete_post'        => 'delete_application', 
            'edit_posts'         => 'edit_applications', 
            'edit_others_posts'  => 'edit_others_applications', 
            'publish_posts'      => 'publish_applications',       
            'read_private_posts' => 'read_private_applications', 
            'create_posts'       => 'edit_applications', 
        ),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-format-aside',
        'supports' => array('title', 'revisions', 'author', 'custom-fields'),

    );


    register_post_type( 'applications', $args );

}

// #####################################################################################

function thet_register_questions(){

    $labels = array(

        'name'          => _x( 'Questions', 'Post type general name', 'textdomain'),
        'singular_name' => _x( 'question', 'Post type singular name', 'textdomain'),
        'menu_name' => _x( 'Questions', 'Admin menu text', 'textdomain'),
        'add_new' => _x( 'Add new', 'Add New on Toolbar', 'textdomain')

    );

    $args = array(

        'labels'   => $labels,
        'description' => 'Question that teams will asnwer',
        'public' => false,
        'exclude_from_search' => true,
        'show_ui' => true,
        'capabilities' => array(
            'edit_post'          => 'edit_question', 
            'read_post'          => 'read_question', 
            'delete_post'        => 'delete_question', 
            'edit_posts'         => 'edit_questions', 
            'edit_others_posts'  => 'edit_others_questions', 
            'publish_posts'      => 'publish_questions',       
            'read_private_posts' => 'read_private_questions', 
            'create_posts'       => 'edit_questions', 
        ),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-format-quote',
        'supports' => array('title', 'revisions', 'author', 'custom-fields', 'editor'),

    );

    register_post_type( 'questions', $args );

}
