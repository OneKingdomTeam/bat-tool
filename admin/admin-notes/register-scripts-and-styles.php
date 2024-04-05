<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function bat_enqueue_admin_notes_block_editor_scripts_and_styles(){

    $current_post = get_post();
    
    if( $current_post && $current_post->post_type === 'report' ){

        wp_register_script('bat_notes_editor_script', bat_get_env()['root_dir_url'] . 'admin/js/extend-editor.js', ['jquery'], false, true);
        wp_localize_script('bat_notes_editor_script', 'batNotesEditorLocalization', [
            'pluginDirUrl' => bat_get_env()['root_dir_url'],
            'reportId' => get_post()->ID,
            'applicationId' => get_post_meta( get_post()->ID, 'connected_application', true),
        ]); 
        wp_enqueue_script('bat_notes_editor_script');
    
    }

}

add_action('enqueue_block_editor_assets', 'bat_enqueue_admin_notes_block_editor_scripts_and_styles');
