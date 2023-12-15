<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function thet_create_admin_notes_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'bat_notes';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        application_id mediumint(9) NOT NULL,
        is_locked TINYINT(1) NOT NULL DEFAULT 0,
        question0_id mediumint(9) DEFAULT 0 NOT NULL,
        note0 text NOT NULL,
        question1_id mediumint(9) DEFAULT 0 NOT NULL,
        note1 text NOT NULL,
        question2_id mediumint(9) DEFAULT 0 NOT NULL,
        note2 text NOT NULL,
        question3_id mediumint(9) DEFAULT 0 NOT NULL,
        note3 text NOT NULL,
        question4_id mediumint(9) DEFAULT 0 NOT NULL,
        note4 text NOT NULL,
        question5_id mediumint(9) DEFAULT 0 NOT NULL,
        note5 text NOT NULL,
        question6_id mediumint(9) DEFAULT 0 NOT NULL,
        note6 text NOT NULL,
        question7_id mediumint(9) DEFAULT 0 NOT NULL,
        note7 text NOT NULL,
        question8_id mediumint(9) DEFAULT 0 NOT NULL,
        note8 text NOT NULL,
        question9_id mediumint(9) DEFAULT 0 NOT NULL,
        note9 text NOT NULL,
        question10_id mediumint(9) DEFAULT 0 NOT NULL,
        note10 text NOT NULL,
        question11_id mediumint(9) DEFAULT 0 NOT NULL,
        note11 text NOT NULL,
        question12_id mediumint(9) DEFAULT 0 NOT NULL,
        note12 text NOT NULL,
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        last_editor_id mediumint(9) DEFAULT 0 NOT NULL,
        tab_hash varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
