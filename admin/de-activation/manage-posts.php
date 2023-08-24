<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function thet_create_pages(){
    
    // Application page
    
    $applications_page_attr = array(

        'post_type' => 'page',
        'post_title' => 'Applications',
        'post_status' => 'publish',
        'post_content' => '<!-- wp:shortcode -->[thet_get_applications]<!-- /wp:shortcode -->',

    );
    
    $interactive_form_page_attr = array(

        'post_type' => 'page',
        'post_title' => 'Interactive form',
        'post_status' => 'publish',

    );


    $applications_page_id = wp_insert_post( $applications_page_attr );
    $interactive_form_page_id = wp_insert_post( $interactive_form_page_attr );

    $thet_pages_ids = array(

        'applications_page_id' => $applications_page_id,
        'interactive_form_page_id' => $interactive_form_page_id,

    );

    if ( empty( get_posts( array( 'post_type' => 'questions'  ) ) ) ){

        thet_create_questions();   

    }

    $thet_options = get_option( 'thet_options');
    if ( $thet_options == false ){
        
        add_option( 'thet_options', $thet_pages_ids );

    }
    
}

function thet_remove_pages(){

    $thet_options = get_option( 'thet_options' );
    
    wp_delete_post( $thet_options['applications_page_id'] , true );
    wp_delete_post( $thet_options['interactive_form_page_id'] , true );

}


function thet_create_questions(){

    
    $questions_attr = array(

        # post title is inserted dynamically
        'post_type' => 'questions',
        'post_status' => 'publish',

    );
    
    $questions_template_data = file_get_contents( plugin_dir_path( __FILE__ ) . 'templates/questions-recent-2023-08-23.json' );
    $questions_template = json_decode( $questions_template_data, true );
    

    for ( $i = 0; $i < 13; $i++ ) {

        
        $questions_attr['post_title'] = $questions_template[ 'beam' . strval( $i ) ]["title"];
        $questions_attr['menu_order'] = $i;
        $question_id = wp_insert_post( $questions_attr );
        
        update_post_meta( $question_id, 'question_data', $questions_template[ 'beam' . strval( $i ) ]);
        

    }

}
