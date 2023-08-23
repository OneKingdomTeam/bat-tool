<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

 // #########################################################################################################################

add_shortcode( 'thet_get_applications', 'thet_get_applications');

function thet_get_applications(){

    if ( ! is_user_logged_in() ){

        ob_start();
        ?>
        <div style="border: 1px solid black; margin-bottom: 0.5rem;">
            <h4>You need to log in:</h4>
            <span>You can visit <a href="<?php echo wp_login_url() ?>">Login page</a> to do so.</span>
        </div>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
        return $output;

    }
    
    $attr = array(

        'post_type' => 'applications',
     
    );

    $applications = get_posts( $attr );

    ob_start(); ?>

    <div>
        <?php

            foreach ($applications as $application) {
                ?>

                <div class="wp-block-columns are-vertically-aligned-center is-layout-flex wp-container-10 wp-block-columns-is-layout-flex" style="border-width:1px;border-radius:1rem;margin-bottom:1rem;padding-top:0.5rem;padding-right:0.5rem;padding-bottom:0.5rem;padding-left:0.5rem">
                <div class="wp-block-column is-vertically-aligned-center is-layout-flow wp-block-column-is-layout-flow" style="padding-top:1rem;padding-right:1rem;padding-bottom:1rem;padding-left:1rem;flex-basis:66.66%">
                <h4 class="wp-block-heading"><?php echo $application->post_title ?></h4>
                <p><?php echo get_the_author_meta( 'display_name', $application->post_author ) ?></p>
                </div>
                <div class="wp-block-column is-vertically-aligned-center is-layout-flow wp-block-column-is-layout-flow" style="flex-basis:33.33%">
                <div class="wp-block-buttons is-content-justification-center is-layout-flex wp-container-8 wp-block-buttons-is-layout-flex">
                <div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
                <div class="wp-block-button is-style-fill"><a href="<?php echo get_permalink( get_option('thet_options')['interactive_form_page_id'] ) . '?application_id=' . $application->ID  ?>" class="wp-block-button__link has-background-color has-pale-cyan-blue-background-color has-text-color has-background wp-element-button" style="border-radius:0.25rem;padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">Edit</a></div>
                </div>
                </div>
                </div>
                </div>

                <?php
            }

        ?>
    </div>
    <?php

    $output = ob_get_contents();
    ob_end_clean();

    return $output;

}


// -------------------------------------------------------------------------------------------------------------------

add_shortcode('thet_get_wheel', 'thet_get_wheel');

function thet_get_wheel(){

    $wheel_content = file_get_contents( plugin_dir_path(__FILE__) . 'media/new-circle-core.svg' );
    return $wheel_content;

}

// -------------------------------------------------------------------------------------------------------------------

add_shortcode( 'thet_testing', 'thet_testing' );

function thet_testing(){

    $questions = get_posts( array( 'post_type' => 'questions' ) );

    ob_start();
    var_dump( $questions );
    $output = ob_get_contents();
    ob_end_clean();
    
    return $output;

}

// -------------------------------------------------------------------------------------------------------------------
