<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'thet_return_wheel', 'thet_return_wheel');

function thet_return_wheel(){

    $svg_circle_location = plugin_dir_path( __FILE__ ) . 'media/new-circle-core.svg';
    $svg_content = file_get_contents( $svg_circle_location );

    return $svg_content;

}
