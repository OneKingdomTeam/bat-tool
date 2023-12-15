<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('load-post-new.php', 'thet_disable_add_new_question_page', 1);

function thet_disable_add_new_question_page(){

    if( $_GET['post_type'] == 'questions' ){

        $args = [
            'response' => 403,
        ];

        wp_die( "Creation of new Questions is forbidden since it's ID is tied to Admin Notes included in the plugin.", "Not allowed", $args );

    }

}



add_action('admin_enqueue_scripts', 'thet_admin_question_enqueue_styles' );
add_action('wp_enqueue_scripts', 'thet_admin_question_enqueue_styles' );

function thet_admin_question_enqueue_styles(){

    wp_enqueue_style( 'thet_admin_questions_styles', plugin_dir_url(__FILE__) . '/css/style.css', false, false);

}
