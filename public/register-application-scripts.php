<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


add_action( 'wp_enqueue_scripts', 'thet_interactive_form_scripts' );

function thet_interactive_form_scripts(){

    global $post;
    if ( $post->ID === get_option( 'thet_options' )['interactive_form_page_id'] ) {

        $dir_url = plugin_dir_url( __FILE__ );

        wp_register_style( 'thet_interactive_form_css', $dir_url . 'css/interactive-form-custom.css', [], NULL );

        wp_register_script( 'thet_interactive_form_questions', $dir_url . 'js/questions.js', [], NULL, true );
        wp_register_script( 'thet_interactive_form_interface', $dir_url . 'js/interface.js', [], NULL, true );
        wp_register_script( 'thet_interactive_form_answers', $dir_url . 'js/answers.js', [], NULL, true );

        wp_register_script(
                'thet_interactive_form_main',
                plugin_dir_url( __FILE__ ) . 'js/main.js',
                [ 'thet_interactive_form_questions', 'thet_interactive_form_interface', 'thet_interactive_form_answers'],
                false,
                true
            );

        $args = [
            'post_type' => 'questions',
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'posts_per_page' => -1
        ];

        $query = new WP_Query( $args );
        $questions = $query->posts;

        $output_array = [];

        foreach( $questions as $question ){

            $current_output = [ 
                'beam' . $question->menu_order => 
                [
                    'title' => $question->post_title,
                    get_post_meta( $question->ID, 'question_data', true ),
                ]
            ];
            array_push( $output_array, $current_output );

        }

        wp_localize_script( 'thet_interactive_form_main', 'thetAjax', [

                'nonce' => wp_create_nonce( 'thet_ajax'),
                'questionsData' => $output_array,

            ] );

        wp_enqueue_style( 'thet_interactive_form_css' );
        wp_enqueue_script( 'thet_interactive_form_main' );

    }

}

