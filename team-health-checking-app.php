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


require_once plugin_dir_path( __FILE__ ) . 'admin/register-post-types.php';
