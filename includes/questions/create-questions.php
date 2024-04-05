<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function bat_create_questions(){

    $questions_attr = [
        # post title is inserted dynamically
        'post_type' => 'questions',
        'post_status' => 'publish',
    ];
    
    $questions_template_data = file_get_contents( bat_get_env()['root_dir_path'] . 'admin/templates/questions-recent.json' );
    $questions_template = json_decode( $questions_template_data, true );
    

    for ( $i = 0; $i < 13; $i++ ) {

        $current_question_content = $questions_template[ 'beam' . sprintf("%02d", $i)];
        $questions_attr['post_title'] = $current_question_content['title'];
        unset( $current_question_content['title'] );
        $questions_attr['menu_order'] = $i;
        $question_id = wp_insert_post( $questions_attr );
        
        update_post_meta( $question_id, 'question_data', $current_question_content );

    }

}


