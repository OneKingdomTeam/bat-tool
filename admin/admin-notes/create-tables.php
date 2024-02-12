<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Crete notes table, notes to question table
// and maps noteID to questionID
function thet_create_admin_notes_tables() {

    thet_create_admin_notes_table();
    thet_create_admin_notes_table_map();
    thet_populate_note_to_question_map();

}

function thet_create_admin_notes_table(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        application_id mediumint(9) NOT NULL,
        is_locked TINYINT(1) NOT NULL DEFAULT 0,
        general_note text NOT NULL,
        note00 text NOT NULL,
        note01 text NOT NULL,
        note02 text NOT NULL,
        note03 text NOT NULL,
        note04 text NOT NULL,
        note05 text NOT NULL,
        note06 text NOT NULL,
        note07 text NOT NULL,
        note08 text NOT NULL,
        note09 text NOT NULL,
        note10 text NOT NULL,
        note11 text NOT NULL,
        note12 text NOT NULL,
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        last_editor_id mediumint(9) DEFAULT 0 NOT NULL,
        session_key varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function thet_create_admin_notes_table_map() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes_map';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        note_id mediumint(9) NOT NULL,
        question_id mediumint(9) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
