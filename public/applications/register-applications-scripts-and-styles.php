<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


add_action( 'wp_enqueue_scripts', 'bat_interactive_form_scripts' );

function bat_interactive_form_scripts(){

    global $post;
    if ( isset( $post ) && $post->ID === get_option( 'bat_options' )['interactive_form_page_id'] ) {

        $dir_url = bat_get_env()['root_dir_url'] . 'public/';

        if ( current_user_can('edit_report') ) {

            // If user is logged in and have right to edit_report (only administrators and form_admins
            wp_register_style('admin_notes_frontend_css', bat_get_env()['root_dir_url'] . 'public/css/admin-notes.css', [], false);
            wp_register_script('admin_notes_frontend_script', bat_get_env()['root_dir_url'] . 'public/js/admin-notes.js', [], false, true);

            wp_localize_script('admin_notes_frontend_script', 'adminNotesLoc', [
                'nonce' => bat_admin_notes_create_frontend_nonce(),
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'notes_map' => bat_get_notes_map(),
            ]);

            wp_enqueue_script('wp-tinymce');
            wp_enqueue_script('wp-editor');
            wp_enqueue_style('admin_notes_frontend_css');
            wp_enqueue_script('admin_notes_frontend_script');

        }

        // bulma CSS is loaded to front end through the theme
        wp_register_style( 'bat_interactive_form_css', $dir_url . 'css/interactive-form-custom.css', [], NULL );

        wp_register_script( 'bat_interactive_form_questions', $dir_url . 'js/questions.js', [], NULL, true );
        wp_register_script( 'bat_interactive_form_interface', $dir_url . 'js/interface.js', [], NULL, true );
        wp_register_script( 'bat_interactive_form_answers', $dir_url . 'js/answers.js', [], NULL, true );
        wp_register_script( 'bat_interactive_form_connector', $dir_url . 'js/connector.js', [], NULL, true );

        wp_register_script(
                'bat_interactive_form_main',
                $dir_url . 'js/main.js',
                [
                    'jquery',
                    'bat_interactive_form_questions',
                    'bat_interactive_form_interface',
                    'bat_interactive_form_answers',
                    'bat_interactive_form_connector',
                ],
                false,
                true
            );

        require_once bat_get_env()['root_dir_path'] . 'includes/questions/supportive-functions.php';
        $questions = bat_get_questions_by_menu_order();

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

        wp_localize_script( 'bat_interactive_form_main', 'batAjax', [

                'nonce' => wp_create_nonce( 'bat_ajax'),
                'questionsData' => $output_array,
                'loaderHTML' => file_get_contents( bat_get_env()['root_dir_path'] . 'public/media/loader-clean.svg' ),

            ]);

        wp_enqueue_style( 'bat_interactive_form_css' );
        wp_enqueue_script( 'bat_interactive_form_main' );

    }

    if ( isset( $post ) && $post->ID === get_option( 'bat_options' )['applications_page_id'] ) {

        $dir_url = plugin_dir_url( __FILE__ );

        wp_register_script( 'bat_application_listing_script', $dir_url . 'js/listing.js', ['jquery'], NULL, true );
        wp_enqueue_script( 'bat_application_listing_script' );

    }

}

