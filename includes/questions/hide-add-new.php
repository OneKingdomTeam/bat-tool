<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('admin_enqueue_scripts', 'bat_enqueue_admin_styles');


function bat_enqueue_admin_styles(){

    wp_enqueue_style('bat_admin_styles', bat_get_env()['root_dir_url'] . 'admin/css/style.css', [], false);

}
