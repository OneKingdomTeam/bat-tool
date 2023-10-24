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
                    <div class="columns mb-5 application-id-<?= $application->ID ?>">
                        <div class="column is-three-fifths is-offset-one-fifth box p-5">
                            <div class="columns">
                                <div class="column">
                                    <div class="wrapper">
                                        <h1 class="title pb-4"><?php echo $application->post_title ?></h1>
                                        <h3 class="subtitle">Owner: <?php echo get_the_author_meta( 'display_name', $application->post_author ) ?></h3>
                                        <div class="columns">

                                            <div class="column">
                                                <div class="thet-last-save-time" data-last-save-time="<?php echo get_post_meta( $application->ID, 'last_save_time', true ) ?>">Last save time:<br><span><?php echo get_post_meta( $application->ID, 'last_save_time', true ) ?></span></div>
                                            </div>
                                            <div class="column">
                                                <div class="thet-last-editor">Last editor:<br><span><?php echo get_userdata( intval( get_post_meta( $application->ID, '_edit_last', true )))->user_login ?></span></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="column is-one-fifth is-flex is-justify-content-center is-align-items-center">
                                    <a href="<?php echo get_permalink( get_option('thet_options')['interactive_form_page_id'] ) . '?application_id=' . $application->ID  ?>">
                                        <div class="button is-primary px-6 thet-listing-edit-button">
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

add_shortcode('thet_get_application_title', 'thet_get_application_title');

function thet_get_application_title(){

    if( isset( $_GET['application_id'] )){

        $user_is_logged_in = is_user_logged_in();
        if( $user_is_logged_in === false ){

            return 'Unauthorised';

        };
            
        $user_can_access = false;

        $required_application_id = intval( $_GET['application_id'] );
        $required_application_object = get_post( $required_application_id );

        $application_author_id = $required_application_object->post_author;
        $requesting_user = wp_get_current_user();


        if ( intval( $requesting_user->ID ) === intval( $application_author_id ) ){
            $user_can_access = true;
        }
        if ( in_array( 'form_admin', $requesting_user->roles ) || in_array( 'administrator', $requesting_user->roles )){
            $user_can_access = true;
        }

        if ( $user_can_access === true ){

            return $required_application_object->post_title;

        }
    
        return 'Unauthorised';

    }


}

// -------------------------------------------------------------------------------------------------------------------

add_shortcode( 'thet_testing', 'thet_testing' );

function thet_testing(){
    
    ob_start();
    $admin_role = get_role( 'form_admin' );
    var_dump( $admin_role );

    return "<pre>" . ob_get_clean() . "</pre>";

}

// -------------------------------------------------------------------------------------------------------------------
