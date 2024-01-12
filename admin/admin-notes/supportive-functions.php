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

