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
                    <div class="columns mb-5">
                        <div class="column is-three-fifths is-offset-one-fifth box p-5">
                            <div class="columns">
                                <div class="column">
                                    <div class="wrapper">
                                        <h1 class="title pb-4"><?php echo $application->post_title ?></h1>
                                        <h2 class="subtitle"><?php echo get_the_author_meta( 'display_name', $application->post_author ) ?></h1>
                                    </div>
                                </div>
                                <div class="column is-one-fifth is-flex is-justify-content-center is-align-items-center">
                                    <a href="<?php echo get_permalink( get_option('thet_options')['interactive_form_page_id'] ) . '?application_id=' . $application->ID  ?>">
                                        <div class="button is-primary px-6">
                                            Edit
                                        </div>
                                    </a>
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
    
    ob_start();
    update_post_meta( 402, 'answers_data', 'hello' );

    var_dump( get_post_meta( 402, 'answers_data' ));
    
    $output = ob_get_clean();
    return "<pre>" . $output . "</pre>";

}

// -------------------------------------------------------------------------------------------------------------------
