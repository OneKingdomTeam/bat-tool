<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_filter( 'use_block_editor_for_post', 'thet_use_classic_editor', 10, 2 );

function thet_use_classic_editor( $use_block_editor, $post ){

    if ( $post->post_type === 'questions' ){

        $use_block_editor = false;

    }
    
    return $use_block_editor;

}
