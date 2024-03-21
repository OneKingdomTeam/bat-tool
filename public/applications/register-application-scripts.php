<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


add_action( 'wp_enqueue_scripts', 'thet_interactive_form_scripts' );

function thet_interactive_form_scripts(){

    global $post;
    if ( is_user_logged_in() && isset( $post ) && $post->ID === get_option( 'thet_options' )['interactive_form_page_id'] ) {

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

        $questions = thet_get_questions_by_menu_order();

        $output_array = [];

        $i = 0;
        foreach( $questions as $question ){

            $question_array = (array) $question;
            
            $beam_number = sprintf("%02d", $i);
            
            $output_array[ 'beam' . $beam_number ]['title'] = $question_array['post_title']; 
            $output_array[ 'beam' . $beam_number ]['question_id'] = $question_array['ID']; 

            $question_data = get_post_meta( $question_array['ID'], 'question_data', true );
            $question_data_array = (array) $question_data;
            $remaining_keys = array_keys( $question_data_array );
            
            foreach( $remaining_keys as $key ){
    
                $output_array[ 'beam' . $beam_number ][$key] = $question_data_array[$key]; 

            };

            $i += 1;

        }

        wp_localize_script( 'thet_interactive_form_main', 'thetAjax', [

                'nonce' => wp_create_nonce( 'thet_ajax'),
                'questionsData' => $output_array,
                'loaderHTML' => file_get_contents( plugin_dir_path( __FILE__ ) . 'media/loader-clean.svg' ),

            ]);

        wp_enqueue_style( 'thet_interactive_form_css' );
        wp_enqueue_script( 'thet_interactive_form_main' );

    }

    if ( is_user_logged_in() && isset( $post ) && $post->ID === get_option( 'thet_options' )['applications_page_id'] ) {

        $dir_url = plugin_dir_url( __FILE__ );

        wp_register_script( 'thet_application_listing_script', $dir_url . 'js/listing.js', ['jquery'], NULL, true );
        wp_enqueue_script( 'thet_application_listing_script' );

    }

}


