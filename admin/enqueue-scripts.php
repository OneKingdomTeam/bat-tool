<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('admin_enqueue_scripts', 'thet_admin_enqueue_scripts');

function thet_admin_enqueue_scripts(){

    wp_register_script( 'thet_admin_js', plugin_dir_url(__FILE__) . 'js/admin.js', array(),  '1.0', true);
    wp_localize_script( 'thet_admin_js', 'thet', array(
        
        'root' => esc_url_raw( rest_url() ),
        'nonce' => wp_create_nonce( 'wp_rest' )

    ) );
    wp_enqueue_script('thet_admin_js');

}
