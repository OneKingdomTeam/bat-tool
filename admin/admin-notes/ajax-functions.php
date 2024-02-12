<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function thet_ajax_get_notes() {

    // Check if user is logged in
    if ( !thet_check_is_user_logged_in_and_admin() ) {
        wp_send_json_error('Unauthorized: User is not logged in.', 401);
    }

    if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
        wp_send_json_error('GET request not supported', 403);
    }


    // Verify nonce first
    if ( !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'admin_notes_frontend_nonce' )) {
        wp_send_json_error('Invalid nonce or nonce not set.', 403);
    }

    if( !isset($_POST['session_key']) ){
        wp_send_json_error('No session key provided.', 403);
    }

    // Check user roles
    $user = wp_get_current_user();
    if (!in_array('administrator', $user->roles) && !in_array('form_admin', $user->roles)) {
        wp_send_json_error('Unauthorized: User does not have permission.', 403);
    }


    // Retrieve the notes
    $application_id = isset($_POST['application_id']) ? intval($_POST['application_id']) : wp_send_json_error('No application ID provided', 500);
    $current_editor_id = $user->ID;
    $session_key = sanitize_text_field( $_POST['session_key'] );
    $notes = thet_get_notes_by_application_id( $application_id, $current_editor_id, $session_key);

    // Return the notes as JSON
    wp_send_json_success($notes);

}

add_action('wp_ajax_thet_ajax_get_notes', 'thet_ajax_get_notes');

function thet_ajax_update_note() {

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_send_json_error('Invalid request method. Only POST requests are allowed.', 405);
    }

    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('Unauthorized: User is not logged in.', 401);
    }

    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'admin_notes_frontend_nonce')) {
        wp_send_json_error('Invalid nonce or nonce not set.', 403);
    }

    // Check user roles
    $user = wp_get_current_user();
    if (!in_array('administrator', $user->roles) && !in_array('form_admin', $user->roles)) {
        wp_send_json_error('Unauthorized: User does not have permission.', 403);
    }

    // Expecting notes_data to be passed in POST request
    if (!isset($_POST['notes_data'])) {
        wp_send_json_error('Error: No note data provided.', 400);
    }

    $notes_data = isset($_POST['notes_data']) && $_POST['notes_data'] != '' ? json_decode(stripslashes($_POST['notes_data'])) : wp_send_json_error('Invalid notes_data data.', 403);

    $update_result = thet_update_admin_note($notes_data);
    $is_error = 0;

    if ( str_contains($update_result, 'Error') ){
        $is_error = true;
    }

    // Check for errors in update
    if ($is_error) {
        wp_send_json_error($update_result, 500);
    } else {
        wp_send_json_success('Note updated successfully.');
    }

}

add_action('wp_ajax_thet_ajax_update_note', 'thet_ajax_update_note');

function thet_ajax_lock_and_unlock_note(){

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_send_json_error('Invalid request method. Only POST requests are allowed.', 405);
    }

    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('Unauthorized: User is not logged in.', 401);
    }

    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'admin_notes_frontend_nonce')) {
        wp_send_json_error('Invalid nonce or nonce not set.', 403);
    }

    // Check user roles
    $user = wp_get_current_user();
    if (!in_array('administrator', $user->roles) && !in_array('form_admin', $user->roles)) {
        wp_send_json_error('Unauthorized: User does not have permission.', 403);
    }

    // Expecting notes_data to be passed in POST request
    if (!isset($_POST['notes_data'])) {
        wp_send_json_error('Error: No note data provided.', 400);
    }

    $notes_data = isset($_POST['notes_data']) && $_POST['notes_data'] != '' ? json_decode(stripslashes($_POST['notes_data'])) : wp_send_json_error('Invalid notes_data data.', 403);

    if( $notes_data['action'] == 'thet_ajax_lock_note' ) {


    } 

    if( $notes_data['action'] == 'thet_ajax_unlock_note' ) {

    }

}


add_action('wp_ajax_thet_ajax_lock_note', 'thet_ajax_lock_and_unlock_note');
add_action('wp_ajax_thet_ajax_unlock_note', 'thet_ajax_lock_and_unlock_note');

function thet_sanitize_notes_data( $notes_data ){

    foreach ($notes_data as $key => $value) {

        if ( str_contains($key, '_id' )){
            $notes_data[$key] = intval($value);
        };
        if ( str_contains($key, 'note' )){
            $notes_data[$key] = sanitize_text_field($value);
        };
    }

    return $notes_data;

}

function thet_notes_testing(){

    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes';

    $query = "SHOW COLUMNS FROM $table_name";
    $results = $wpdb->get_results($query);

    $columns = array();
    foreach ($results as $column) {
        $columns[] = $column->Field;
    }

    wp_send_json($column);

}

add_action('wp_ajax_thet_notes_testing', 'thet_notes_testing');
