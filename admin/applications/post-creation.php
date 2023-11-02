<?php 

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('save_post_applications', 'thet_save_application_changes');


function thet_save_application_changes(){


    if ( str_starts_with( $_POST['_wp_http_referer'], '/wp-admin/post-new.php') && $_POST['post_type'] === 'applications' ){

        update_post_meta( $_POST['ID'] , 'last_save_time', time() );

        $file = fopen( plugin_dir_path(__FILE__) . 'log.txt', "w" ) or die('tadafdada');
        ob_start();
        var_dump( $_POST );
        var_dump( '\n\n\n\n' );
        var_dump( 'time was updated' );
        fwrite( $file, ob_get_clean() );
        fclose( $file );
        unset( $file );

    }

}

