<?php
// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function bat_populate_note_to_question_map(){

    require_once bat_get_env()['root_dir_path'] . 'includes/questions/supportive-functions.php';
    $current_questions = bat_get_questions_by_menu_order();
    $it = 0;

    $notes_map = bat_get_notes_map();

    if ( count( $notes_map ) !== 0 ){
        return;
    }
    
    foreach ($current_questions as $question) {

        $note_id = $it;
        $question_id = $question->ID;
        bat_insert_into_note_to_question_map($note_id, $question_id);

        $it += 1;
        
    }

}

function bat_admin_notes_create_frontend_nonce():string {

    $admin_notes_frontend_nonce = wp_create_nonce('admin_notes_frontend_nonce');
    return $admin_notes_frontend_nonce;

}

function bat_generate_random_string( int $leght): string {

    $characters = 'qwertyuiopasdfghjklzxcvbnm';
    $output = '';
    for( $i = 0; $i < $leght - 1; $i++ ){
        $output .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $output;
    
}

function bat_remove_protected_from_report_title() {

    // By default WP attaches "Protected: " to the post title
    // it looks odd. So we need to get rid of it.
    return __('%s');

}

add_filter( 'protected_title_format', 'bat_remove_protected_from_report_title' );

function bat_get_notes_by_application_id(int $application_id, int $current_editor_id, string $current_session_key)  {

    global $wpdb;

    $table_name = $wpdb->prefix . 'bat_notes';


    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE application_id = %d", $application_id);

    $row = $wpdb->get_row($sql);

    if(!$row){

        bat_notes_create_row_if_not_exists( $application_id, $current_editor_id, $current_session_key );
        $row = $wpdb->get_row($sql);

    }

    return $wpdb->get_row($sql);

}


function bat_only_get_notes_by_application_id(int $application_id)  {

    global $wpdb;

    $table_name = $wpdb->prefix . 'bat_notes';

    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE application_id = %d", $application_id);

    return $wpdb->get_row($sql);

}

function bat_update_admin_note($note_data): array {

    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes';

    $application_id = intval( $note_data->application_id );
    $current_editor_id = intval( wp_get_current_user()->ID );
    $current_session_key = $note_data->session_key;

    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE application_id = %d", $application_id));

    if (!$row) {
        return [
            'success'=> false,
            'message'=> 'Row does not exist'
        ];
    }

    $is_locked = intval( $row->is_locked );
    $last_editor_id = intval( $row->last_editor_id );
    $last_update = strtotime($row->last_updated);
    $session_key = $row->session_key;
    $five_minutes_ago = time() - (5 * 60);

    /*
    if ($is_locked === 1 ) {

        if ($last_update < $five_minutes_ago) {
            $note_data->is_locked = true;
        } elseif ($current_editor_id === $last_editor_id && $current_session_key == $session_key) {
            $note_data->is_locked = true;
        } else {
            return [
                'success' => false,
                'message' => 'Note is being editted by someone else'
            ];
        }
    }
*/

    unset($note_data->id);

    $result = $wpdb->update($table_name, (array)$note_data, array('application_id' => $application_id));

    if ($result === false) {
        return [
            'success' => false,
            'message' => 'Unable to edit the entry'
        ];
    }

    return [
            'success' => true,
            'message' => 'Entry sucessfuly updated.'
    ];

}

function bat_unlock_admin_note( int $application_id, string $session_key ): array {

    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes';

    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE application_id = %d", $application_id));

    if (!$row) {
        return [
            'success' => false,
            'message' => 'Row not found'
        ];
    }

    if ( intval( $row->is_locked ) === 0 ){
        return [
            'success' => true,
            'message' => 'Row was already unlocked'
        ];
    }

    if ( intval( $row->last_editor_id ) !== get_current_user_id() ){
        return [
            'success' => false,
            'message' => 'You are not the one who locked the note'
        ];
    }

    // HAVE TO ADD THE CHECK OF IF YOUR SESSION KEY MATCHES
    // LATEST EDITOR SESSION KEY AND IF NOT THAN IF THE 
    // TIME SINCE LAST EDIT IS LARGER THAN LETS SAY 5 MINUTS

    $sql = $wpdb->prepare(
        "UPDATE $table_name SET is_locked = %d WHERE application_id = %d",
        0,
        $application_id
    );

    $result = $wpdb->query($sql);

    if ( false === $result ) {
        return [
            'success' => false,
            'message' => $wpdb->last_error
        ];
    } else if ( $result === 0 ){
        return [
            'success' => false,
            'message' => 'No rows affected'
        ];
    } else {
        return [
            'success' => true,
            'message' => 'Successfuly unlocked'
        ];
    }

}

function bat_lock_admin_note(int $application_id, string $current_session_key ):array {

    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes';

    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE application_id = %d", $application_id));

    if (!$row) {
        return [
            'success' => false,
            'message' => 'Row not found'
        ];
    }

    if ( intval( $row->is_locked ) === 1 ){
        return [
            'success' => true,
            'message' => 'Row was already locked'
        ];
    }

    $sql = $wpdb->prepare(
        "UPDATE $table_name SET is_locked = %d, session_key = %s WHERE application_id = %d",
        1,
        $current_session_key,
        $application_id
    );

    $result = $wpdb->query($sql);

    if ( false === $result ) {
        return [
            'success' => false,
            'message' => $wpdb->last_error
        ];
    } else if ( $result === 0 ){
        return [
            'success' => false,
            'message' => 'No rows affected',
        ];
    } else {
        return [
            'success' => true,
            'message' => 'Successfuly locked'
        ];
    }
    
}

function bat_notes_create_row_if_not_exists( $application_id, $current_editor_id, $current_session_key ){

    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes';

    $columns_names = bat_get_notes_table_column_names();

    require_once bat_get_env()['root_dir_path'] . 'includes/questions/supportive-functions.php';
    $questions = bat_get_questions_by_menu_order();


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

function bat_get_notes_table_column_names(){

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

function bat_insert_into_note_to_question_map($note_id, $question_id){

    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes_map';

    $query = $wpdb->prepare("INSERT INTO $table_name (note_id, question_id) VALUES (%d, %d)", $note_id, $question_id );
    $wpdb->query($query);

}

function bat_get_notes_map()  {

    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes_map';
    $result = $wpdb->get_results("SELECT * FROM $table_name");
    return $result;

}

