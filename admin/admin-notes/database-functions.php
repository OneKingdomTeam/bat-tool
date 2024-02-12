<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function thet_get_notes_by_application_id(int $application_id, int $current_editor_id, string $current_session_key)  {

    global $wpdb;

    $table_name = $wpdb->prefix . 'bat_notes';


    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE application_id = %d", $application_id);

    $row = $wpdb->get_row($sql);

    if(!$row){

        thet_notes_create_row_if_not_exists( $application_id, $current_editor_id, $current_session_key );
        $row = $wpdb->get_row($sql);

    }

    return $wpdb->get_row($sql);

}

function thet_update_admin_note($note_data) {

    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes';

    $application_id = intval( $note_data->application_id );
    $current_editor_id = intval( $note_data->last_editor_id ) ;
    $current_session_key = $note_data->session_key;

    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE application_id = %d", $application_id));

    if (!$row) {
        return [
            'success'=> false,
            'message'=> 'Row does not exist'
        ];
    }

    $is_locked = $row->is_locked;
    $last_editor_id = $row->last_editor_id;
    $last_update = strtotime($row->last_updated);
    $session_key = $row->session_key;
    $five_minutes_ago = time() - (5 * 60);

    if ($is_locked) {

        if ($last_update < $five_minutes_ago) {
            $note_data->is_locked = true;
        } elseif ($current_editor_id == $last_editor_id && $current_session_key == $session_key) {
            $note_data->is_locked = true;
        } else {
            return [
                'success' => false,
                'message' => 'Note is being editted by someone else'
            ];
        }

    }

    unset($note_data->id);

    $result = $wpdb->update($table_name, (array)$note_data, array('application_id' => $application_id));

    if ($result === false) {
        return [
            'success' => false,
            'message' => 'Unable to edit the entry'
        ];
    }

    return 'Entry updated successfully.';

}

function thet_unlock_admin_note( $note_data ){

    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes';

    $application_id = intval( $note_data->application_id );
    $current_editor_id = intval( $note_data->last_editor_id ) ;
    $current_session_key = $note_data->session_key;

    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE application_id = %d", $application_id));

    if (!$row) {
        return [
            'success' => false,
            'message' => 'Row not found'
        ];
    }

    if ( $row->is_locked === 0 ){
        return [
            'success' => false,
            'message' => 'Row is already unlocked'
        ];
    }

    if ( $row->last_editor_id !== get_current_user_id() ){
        return [
            'success' => false,
            'message' => 'You are not the one who locked the note'
        ];
    }

    $sql = $wpdb->prepare("UPDATE %s SET is_locked=1 WHERE application_id=%d", $table_name, $application_id);
    $result = $wpdb->get_results($sql);
    if ( $wpdb->last_error ) {
        return [
            'success' => false,
            'message' => $wpdb->last_error
        ];
    }

    return [
        'success' => true,
        'message' => 'Successfuly locked'
    ];
    
}

function thet_lock_admin_note( $note_data ){

    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes';

    $application_id = intval( $note_data->application_id );
    $current_editor_id = intval( $note_data->last_editor_id ) ;
    $current_session_key = $note_data->session_key;

    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE application_id = %d", $application_id));

    if (!$row) {
        return [
            'success' => false,
            'message' => 'Row not found'
        ];
    }

    if ( $row->is_locked === 1 ){
        return [
            'success' => false,
            'message' => 'Row already locked'
        ];
    }

    $sql = $wpdb->prepare("UPDATE %s SET is_locked=1 WHERE application_id=%d", $table_name, $application_id);
    $result = $wpdb->get_results($sql);
    if ( $wpdb->last_error ) {
        return [
            'success' => false,
            'message' => $wpdb->last_error
        ];
    }

    return [
        'success' => true,
        'message' => 'Successfuly locked'
    ];
    
}

function thet_notes_create_row_if_not_exists( $application_id, $current_editor_id, $current_session_key ){

    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes';

    $columns_names = thet_get_notes_table_column_names();
    $questions = thet_get_questions_by_menu_order();


    $data = [];

    $question_iteration = 0;
    foreach ($columns_names as $col_name) {

        if( str_contains( $col_name, 'question' )){
            $data[$col_name] = $questions[$question_iteration]->ID;
            $question_iteration += 1;
            continue;
        } elseif ( str_contains( $col_name, 'note' )){
            $data[$col_name] = '';
            continue;
        }

        $data[$col_name];

     }

    $data['application_id'] = $application_id;
    $data['last_editor_id'] = $current_editor_id;
    $data['session_key'] = $current_session_key;

    $wpdb = $wpdb->replace( $table_name, $data);

}

function thet_get_notes_table_column_names(){

    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes';

    $query = "SHOW COLUMNS FROM $table_name";
    $results = $wpdb->get_results($query);

    $columns_names = array();
    foreach ($results as $column) {
        $columns_names[] = $column->Field;
    }

    return $columns_names;
}

function thet_insert_into_note_to_question_map($note_id, $question_id){

    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes_map';

    $query = $wpdb->prepare("INSERT INTO $table_name (note_id, question_id) VALUES (%d, %d)", $note_id, $question_id );
    $wpdb->query($query);

}
