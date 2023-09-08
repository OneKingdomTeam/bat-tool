<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_filter('editable_roles', 'thet_restrict_editable_roles');

function thet_restrict_editable_roles( $roles ){

    if ( in_array( 'form_admin',  wp_get_current_user()->roles ) ){

        $allowed_roles = ['form_admin', 'form_user'];

        foreach( $roles as $role_name => $role_info ){
            
            if ( !in_array( $role_name, $allowed_roles )){

                unset( $roles[$role_name] );

            }

        };
        
        return $roles;
    
    } else {

        return $roles;

    }

}
