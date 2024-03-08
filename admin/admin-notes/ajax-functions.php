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

    // Check for errors in update
    if ( $update_result['success'] === false ) {
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

    if( $_POST['action'] == 'thet_ajax_lock_note' ) {

        $result = thet_lock_admin_note( intval( $_POST['application_id'] ), $_POST['session_key'] );
        
        if ( $result['success'] ) {
            wp_send_json( $result, 200 );
        } else {
            wp_send_json_error( $result, 400 );
        }

    } 

    if( $_POST['action'] == 'thet_ajax_unlock_note' ) {

        $result = thet_unlock_admin_note( intval( $_POST['application_id'] ), $_POST['session_key'] );

        if ( $result['success'] ) {
            wp_send_json( $result, 200 );
        } else {
            wp_send_json_error( $result, 400 );
        }

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

function thet_ajax_get_notes_map(){

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

    

}

add_action('wp_ajax_thet_ajax_get_notes_map', 'thet_ajax_get_notes_map');


function thet_handle_reports_ajax(){

    if ( !current_user_can('edit_report') ){
        wp_send_json_error('Unauthorzied to do that', 401);
    }

    if ( !$_POST ){
        wp_send_json_error('Only POST method is allowed', 401);
    }


    if( $_POST['subaction'] === 'get_available_applications' ){

        $attr = [
            'post_type' => 'applications',
            'posts_per_page' => -1,
        ];
        $applications = get_posts( $attr );

        $output = [];

        foreach($applications as $application){
            array_push($output, ['ID' => $application->ID, 'title' => $application->post_title]);
        }

        if ( count( $output ) === 0 ){
            wp_send_json_error(['success'=>false, 'message' => 'No applications found yet'], 400);
        }
        wp_send_json( $output );

    }

    if( $_POST['subaction'] === 'set_application_id_for_current_report' ){

        $new_application_id = intval( $_POST['application_id'] );
        $report_id = intval( $_POST['report_id'] );


        $current_val = intval( get_post_meta( $report_id, 'connected_application', true));

        if ( $current_val === $new_application_id ){
            wp_send_json(['success' => true, 'message' => 'Attempted to store the same value']);
        } else {
            if( metadata_exists('reports', $report_id, 'connected_application') ){
                $result = update_post_meta( $report_id, 'connected_application', $new_application_id);
            } else {
                $result = add_post_meta( $report_id, 'connected_application', $new_application_id);
            }
        }

        if ( $result !== false ){
            wp_send_json(['success' => true, 'message' => 'Succesfully updated!']);
        } else {
            wp_send_json_error(['success' => false, 'message' => 'Something went wrong while updating conneted_application.'], 400);
        }


    }

    if( $_POST['subaction'] === 'get_application_notes' ){
        $notes_data = thet_get_notes_by_application_id( intval($_POST['application_id']), wp_get_current_user()->ID, "");
        $notes_map = thet_get_notes_map();

        $output = "";


        $output .= "<div class \"content\">";
        foreach($notes_map as $entry){
            $note_id = $entry->note_id;
            $question_id = $entry->question_id;
            
            $note_id_formatted = str_pad($note_id, 2, '0', STR_PAD_LEFT);
            $question_title = get_post( intval( $question_id ) )->post_title;
            
            $notes_data_array = (array) $notes_data;

            $output .= "<h3>{$question_title}</h3>{$notes_data_array['note' . $note_id_formatted]}";

        }

        wp_send_json( $output );

    }
    

}

add_action('wp_ajax_thet_ajax_reports', 'thet_handle_reports_ajax');
