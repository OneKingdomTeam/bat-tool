<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'bat_get_applications', 'bat_get_applications');

function bat_get_applications(){

    $attr = array(

        'post_type' => 'application',
        'posts_per_page' => -1
     
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
                                <div class="column is-three-quarters">
                                    <div class="wrapper">
                                        <h1 class="title pb-4"><?php echo $application->post_title ?></h1>
                                        <h3 class="subtitle">Owner: <?php echo get_the_author_meta( 'display_name', $application->post_author ) ?></h3>
                                        <div class="columns">

                                            <div class="column">
                                                <div class="bat-last-save-time" data-last-save-time="<?php echo get_post_meta( $application->ID, 'last_save_time', true ) ?>">Last save time:<br><span><?php echo get_post_meta( $application->ID, 'last_save_time', true ) ?></span></div>
                                            </div>
                                            <div class="column">
                                                <div class="bat-last-editor">Last editor:<br><span><?php echo get_userdata( intval( get_post_meta( $application->ID, '_edit_last', true )))->user_login ?></span></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="column is-one-quarter is-flex is-justify-content-center is-align-items-center is-flex-direction-column" style="gap: 1rem;">
                                    <a class="button is-primary is-fullwidth" href="<?php echo get_permalink( get_option('bat_options')['interactive_form_page_id'] ) . '?application_id=' . $application->ID  ?>">
                                        Edit
                                    </a>
                                    <?php
                                        
                                        $reports_atts = [
                                            'post_type' => 'reports',
                                            'posts_per_page' => -1,
                                            'meta_query' => [
                                                [
                                                    'key' => 'connected_application',
                                                    'value' => $application->ID,
                                                    'compare' => '=',
                                                    'type' => 'NUMERIC',
                                                ]
                                            ],
                                        ];
                                        $attached_reports = get_posts( $reports_atts );

                                        if ( count( $attached_reports ) !== 0 ){

                                            ?>
                                                <a href="#" class="button is-warning is-light is-fullwidth view-report-btn" data-report_url="<?= get_post_permalink( $attached_reports[0]->ID ) ?>" data-report_password="<?= $attached_reports[0]->post_password; ?>">
                                                    View report
                                                </a>
                                            <?php
                                        }
                                        
                                    ?>
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

add_shortcode('bat_get_wheel', 'bat_get_wheel');

function bat_get_wheel(){

    $wheel_content = file_get_contents( bat_get_env()['root_dir_path'] . 'public/media/new-circle-core.svg' );
    return $wheel_content;

}

// -------------------------------------------------------------------------------------------------------------------

add_shortcode('bat_get_application_title', 'bat_get_application_title');

function bat_get_application_title(){

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


add_shortcode( 'bat_testing', 'bat_testing' );

function bat_testing(){
    
    ob_start();
    $question_data = bat_get_questions_by_menu_order();
    var_dump( $question_data );

   return "<pre>" . ob_get_clean() . "</pre>";

}
