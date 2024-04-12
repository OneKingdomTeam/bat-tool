<?php
// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Loads function that then returns the required capabilities for two main
// roles. form_user and form_admin
require_once( plugin_dir_path(__FILE__) . 'user-roles-and-capabilities.php' );


function bat_remove_new_user_roles() {

    $user_roles_capabilities = bat_return_user_roles_capabilities();

    remove_role('form_admin');
    remove_role('form_user');
    bat_remove_capabilities_from_admin( $user_roles_capabilities );

}


function bat_remove_capabilities_from_admin( $user_roles_capabilities ){

    $admin_role = get_role( 'administrator' );

    $admin_extra_capabilities = $user_roles_capabilities['administrator_capabilities'];
    foreach( $admin_extra_capabilities as $capability) {

        $admin_role->remove_cap( $capability );

    }

}

