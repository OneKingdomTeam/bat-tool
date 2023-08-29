<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


add_action( 'wp_enqueue_scripts', 'thet_interactive_form_scripts' );

function thet_interactive_form_scripts(){

    global $post;
    if ( isset( $post ) && $post->ID === get_option( 'thet_options' )['interactive_form_page_id'] ) {

        $dir_url = plugin_dir_url( __FILE__ );

        wp_register_style( 'thet_interactive_form_css', $dir_url . 'css/interactive-form-custom.css', [], NULL );

        wp_register_script( 'thet_interactive_form_questions', $dir_url . 'js/questions.js', [], NULL, true );
        wp_register_script( 'thet_interactive_form_interface', $dir_url . 'js/interface.js', [], NULL, true );
        wp_register_script( 'thet_interactive_form_answers', $dir_url . 'js/answers.js', [], NULL, true );
        wp_register_script( 'thet_interactive_form_connector', $dir_url . 'js/connector.js', [], NULL, true );

        wp_register_script(
                'thet_interactive_form_main',
                plugin_dir_url( __FILE__ ) . 'js/main.js',
                [
                    'thet_interactive_form_questions',
                    'thet_interactive_form_interface',
                    'thet_interactive_form_answers',
                    'thet_interactive_form_connector',
                ],
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
            
            $question_array = (array) $question;
            $output_array[ 'beam' . $question_array['menu_order'] ]['title'] = $question_array['post_title']; 

            $question_data = get_post_meta( $question_array['ID'], 'question_data', true );
            $question_data_array = (array) $question_data;
            $remaining_keys = array_keys( $question_data_array );

            foreach( $remaining_keys as $key ){
    
                $output_array[ 'beam' . $question_array['menu_order'] ][$key] = $question_data_array[$key]; 

            };

        }

        wp_localize_script( 'thet_interactive_form_main', 'thetAjax', [

                'nonce' => wp_create_nonce( 'thet_ajax'),
                'questionsData' => $output_array,

            ]);

        wp_enqueue_style( 'thet_interactive_form_css' );
        wp_enqueue_script( 'thet_interactive_form_main' );

    }

}

