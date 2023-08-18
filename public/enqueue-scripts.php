<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


add_action('wp_enqueue_scripts', 'thet_application_enqueue_scripts');

function thet_application_enqueue_scripts(){

    wp_register_script( 'thet_application_js_answers', plugin_dir_url(__FILE__) . 'js/answers.js', array(),  '1.0', true);
    wp_register_script( 'thet_application_js_interface', plugin_dir_url(__FILE__) . 'js/interface.js', array(),  '1.0', true);
    wp_register_script( 'thet_application_js_questions', plugin_dir_url(__FILE__) . 'js/questions.js', array(),  '1.0', true);
    wp_register_script( 'thet_application_js_main', plugin_dir_url(__FILE__) . 'js/main.js', array(),  '1.0', true);

    wp_localize_script( 'thet_application_js_main', 'thet', array(
        
        'root' => esc_url_raw( rest_url() ),
        'nonce' => wp_create_nonce( 'wp_rest' )

    ) );

    wp_enqueue_script('thet_application_js_questions');
    wp_enqueue_script('thet_application_js_answers');
    wp_enqueue_script('thet_application_js_interface');
    wp_enqueue_script('thet_application_js_main');

}
