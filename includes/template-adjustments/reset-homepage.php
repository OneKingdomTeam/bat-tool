<?php
// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function bat_reset_homepage_settings(){

    update_option('page_on_front', 0);
    update_option('show_on_front', 'posts');

}

