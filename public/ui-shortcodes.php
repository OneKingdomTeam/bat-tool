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
                <div style="border: 1px solid black; margin-bottom: 0.5rem; display: flex; flex-direction: row;">
                    <div style="width: 70%;">
                        <h4><?php echo $application->post_title; ?></h4>
                        <span><?php echo get_the_author_meta( 'display_name', $application->post_author ); ?></span>
                    </div>
                    <div style="width: 30%; display: flex; flex-direction: column; justify-content: center; padding-left: 0.5rem; padding-right: 0.5rem;;">
                        <a href="<?php echo get_permalink( get_option( 'thet_options' )['interactive_form_page_id'] ) . '?application_id=' . strval( $application->ID ); ?>">
                            <div style="padding: 0.8rem 1.5rem; border: 1px solid black; text-align: center;">Edit application</div>
                        </a>
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

add_shortcode( 'thet_testing', 'thet_testing' );

function thet_testing(){

    $attr = array(

        'post_type' => 'applications',
     
    );

    $applications = get_posts( $attr );

    ob_start();
    ?>
    <script>let thetTesting = <?php echo json_encode( $applications ); ?></script>
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;

}


