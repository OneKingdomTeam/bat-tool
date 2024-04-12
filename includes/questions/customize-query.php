<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'pre_get_posts', 'thet_customize_questions_query');

function thet_customize_questions_query( $query ){

    if ( $query->is_main_query() && $query->get('post_type') === 'questions' ){

        $query->set( 'orderby', 'menu_order' );
        $query->set( 'order', 'ASC' );

    }

}
