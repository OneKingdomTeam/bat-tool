<?php
// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function thet_populate_note_to_question_map(){

    $current_questions = thet_get_questions_by_menu_order();
    $it = 0;
    
    foreach ($current_questions as $question) {

        $note_id = $it;
        $question_id = $question->ID;
        thet_insert_into_note_to_question_map($note_id, $question_id);

        $it += 1;
        
    }

}


function thet_admin_notes_create_frontend_nonce():string {

    $admin_notes_frontend_nonce = wp_create_nonce('admin_notes_frontend_nonce');
    return $admin_notes_frontend_nonce;

}



function thet_generate_random_string( int $leght): string {

    $characters = 'qwertyuiopasdfghjklzxcvbnm';
    $output = '';
    for( $i = 0; $i < $leght - 1; $i++ ){
        $output .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $output;
    
}

function thet_remove_protected_from_report_title() {

    return __('%s');

}

add_filter( 'protected_title_format', 'thet_remove_protected_from_report_title' );
