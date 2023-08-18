<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function thet_return_custom_templates_data(){

    $thet_custom_templates = array();
    
    $thet_custom_templates['thet-instance-listing.php'] = 'Thet Instance Listing';
    $thet_custom_templates['thet-interactive-form.php'] = 'Thet Interactive Form';

    return $thet_custom_templates;

}

add_filter( 'theme_page_templates', 'thet_register_custom_templates', 10, 3);

function thet_register_custom_templates( $page_templates , $theme, $post ){

    $templates = thet_return_custom_templates_data();

    foreach( $templates as $key => $value ){

        $page_templates[$key] = $value;
    
    }

    return $page_templates;

}


add_filter( 'template_include', 'thet_include_templates', 99);

function thet_include_templates( $template ){

    global $post;

    $page_template_slug = get_page_template_slug( $post->ID );

    $thet_templates = thet_return_custom_templates_data();

    if ( isset( $thet_templates[ $page_template_slug ] )) {

        $template = plugin_dir_path(__FILE__) . 'templates/' . $page_template_slug;
    
    }
    
    return $template;

}


