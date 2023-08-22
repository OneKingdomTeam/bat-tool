<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}



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
                <div style="border: 1px solid black; margin-bottom: 0.5rem;">
                    <h4><?php echo $application->post_title; ?></h4>
                    <span><?php echo get_the_author_meta( 'display_name', $application->post_author ); ?></span>
                    <a href=""><div></div>
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

add_shortcode( 'thet_testing', 'thet_testing' );

function thet_testing(){

    $block_templates = get_block_templates();

    $json_output = json_encode( $block_templates );
    $wrapper = '<script>let thetTeting = ' . $json_output . '</script>';

    return $wrapper;

}


