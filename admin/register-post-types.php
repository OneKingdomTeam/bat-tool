<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('init', 'thet_register_applications');

function thet_register_applications() {

    $labels = array(

        'name'          => _x( 'Applications', 'Post type general name', 'textdomain'),
        'singular_name' => _x( 'Application', 'Post type singular name', 'textdomain'),
        'menu_name' => _x( 'Applications', 'Admin menu text', 'textdomain'),
        'add_new' => _x( 'Add new', 'Add New on Toolbar', 'textdomain')

    );

    $args = array(

        'label'   => $labels,
        'description' => 'Form applications of the teams.',
        'public' => false,
        'exclude_from_search' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-format-aside',
        'supports' => array('title', 'revisions', 'author', 'custom-fields', 'editor'),

    );


    register_post_type( 'applications', $args );

}
