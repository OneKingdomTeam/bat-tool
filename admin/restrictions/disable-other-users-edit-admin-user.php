<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('load-user-edit.php', 'bat_disable_admin_user_changes');

function bat_disable_admin_user_changes( ){

    if ( $_GET['user_id'] === "1" ){

        wp_die('Unauthorised');

    }

}

