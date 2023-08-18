<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'thet_interactive_form', 'thet_interactive_form' );

function thet_interactive_form(){
    
    $interactive_form = '<div>';

    return $interactive_form;

}
