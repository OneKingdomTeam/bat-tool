<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function thet_enqueue_admin_notes_block_editor_scripts_and_styles(){

    $current_post = get_post();
    
    if( $current_post && $current_post->post_type === 'reports' ){

        global $thet_plugin_environment;

        wp_register_script('thet_notes_editor_script', plugin_dir_url(__FILE__) . 'js/extend-editor.js', [], false, true);
        wp_localize_script('thet_notes_editor_script', 'thetNotesEditorLocalization', [
            'pluginDirUrl' => $thet_plugin_environment['root_dir_url'],
            'reportId' => get_post()->ID,
            'applicationId' => get_post_meta( get_post()->ID, 'connected_application', true),
        ]); 
        wp_enqueue_script('thet_notes_editor_script');
    
    }

}


add_action('enqueue_block_editor_assets', 'thet_enqueue_admin_notes_block_editor_scripts_and_styles' );
