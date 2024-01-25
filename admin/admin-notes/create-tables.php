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
        note0 text NOT NULL,
        note1 text NOT NULL,
        note2 text NOT NULL,
        note3 text NOT NULL,
        note4 text NOT NULL,
        note5 text NOT NULL,
        note6 text NOT NULL,
        note7 text NOT NULL,
        note8 text NOT NULL,
        note9 text NOT NULL,
        note10 text NOT NULL,
        note11 text NOT NULL,
        note12 text NOT NULL,
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        last_editor_id mediumint(9) DEFAULT 0 NOT NULL,
        tab_hash varchar(255) NOT NULL,
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
