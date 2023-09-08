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

add_action('admin_head', 'thet_customize_questions_columns_width');

function thet_customize_questions_columns_width() {
    global $post_type;
    if ( 'questions' == $post_type ) {
        ?>
            <style type="text/css">
                .column-menu_order {
                    width: 100px;
                    text-align: center;
                }
            </style>
        <?php
    }
}

add_action( 'manage_questions_posts_columns', 'thet_customize_questions_columns' );

function thet_customize_questions_columns( $columns ){

    $new_columns = [
            'cb' => $columns['cb'],
            'menu_order' => 'Order',
            'title' => $columns['title'],
            'date' => $columns['date']
        ];
    return $new_columns;

}

add_action( 'manage_questions_posts_custom_column', 'thet_questions_custom_columns_values', 10, 2 );

function thet_questions_custom_columns_values( $column, $post_id ){

    switch ( $column ){

        case 'menu_order': echo get_post( $post_id )->menu_order; break;

    }

}
