<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function bat_register_questions(){

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
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-format-quote',
        'supports' => [ 'title', 'author' ],

    ];

    register_post_type( 'questions', $args );

}

