<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



function bat_register_applications() {

    $labels = [

        'name'          => _x( 'Applications', 'Post type general name', 'textdomain'),
        'singular_name' => _x( 'Application', 'Post type singular name', 'textdomain'),
        'menu_name' => _x( 'Applications', 'Admin menu text', 'textdomain'),
        'add_new' => _x( 'Add new', 'Add New on Toolbar', 'textdomain')

    ];

    $args = [

        'labels'              => $labels,
        'description'         => 'Form Applications',
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
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-format-aside',
        'supports' => [ 'title', 'author' ],

    ];

    register_post_type( 'application', $args );

}
