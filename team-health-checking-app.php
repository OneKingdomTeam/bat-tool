<?php

/*
Plugin Name:  Team Health Checking App
Description:  Creates UI and backend for health checking app. Manages users, data, etc.
Plugin URI:   https://vrubel.online/
Author:       Petr Vrubel
Version:      1.0
Text Domain:  team-health-checking-app
Domain Path:  /languages
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.txt
*/

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Activation and deactivation hooks
require_once plugin_dir_path( __FILE__ ) . 'admin/register-user-roles.php';


register_activation_hook( __FILE__, 'thet_register_new_user_roles' );
register_deactivation_hook( __FILE__, 'thet_remove_new_user_roles' );



// Register post types and set rules// Register post types and set rules
require_once plugin_dir_path( __FILE__ ) . 'admin/register-post-types.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/query-rules.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/rest-rules.php';


// Enq necessary JS
require_once plugin_dir_path( __FILE__ ) . 'admin/enqueue-scripts.php';
