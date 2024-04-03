<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function bat_get_questions_by_menu_order() {

    $args = [
        'post_type' => 'questions',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'posts_per_page' => -1
        ];

    $query = new WP_Query( $args );
    $questions = $query->posts;

    return $questions;

}

