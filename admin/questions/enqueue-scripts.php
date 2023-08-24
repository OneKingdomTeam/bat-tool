<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_enqueue_scripts', 'thet_question_edit_page_custom_js' );

function thet_question_edit_page_custom_js(){

    global $pagenow, $post;

    if ( isset( $post ) && $post->post_type === 'questions' && $pagenow === 'post.php' ){

        wp_register_script( 'thet_questions_editor_js', plugin_dir_url( __FILE__ ) . 'js/questions-editor.js', [], '1.0.0', true );
        wp_localize_script( 'thet_questions_editor_js', 'thetQEditor', [
            
                'nonce' => wp_create_nonce( 'ajax-nonce' ),
                'data' => [],

            ] );

        wp_register_style( 'thet_questions_editor_css', plugin_dir_url( __FILE__ ) . 'css/questions-editor.css' );

        wp_enqueue_script( 'thet_questions_editor_js' );
        wp_enqueue_style( 'thet_questions_editor_css' );
          
    }

}
