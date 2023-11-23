<?php

/*
Plugin Name:  Team Health Checking App
Description:  Creates UI and backend for health checking app. Manages users, data, etc.
Plugin URI:   https://vrubel.online/
Author:       Petr Vrubel
Version:      1.2
Text Domain:  team-health-checking-app
Domain Path:  /languages
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.txt
*/

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ---------------------- Backend Scripts  ---------------------

// Redirect to login for non-logged in users
require_once plugin_dir_path( __FILE__ ) . 'admin/restrictions/login-redirect.php';

// Restrict wp-admin for form-user
require_once plugin_dir_path( __FILE__ ) . 'admin/restrictions/hide-admin.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/restrictions/disable-admin-user-changes.php';

// Activation and deactivation hooks
require_once plugin_dir_path( __FILE__ ) . 'admin/de-activation/de-activation.php';

register_activation_hook( __FILE__, 'thet_activation' );
register_deactivation_hook( __FILE__, 'thet_deactivation' );



// Register post types and set rules// Register post types and set rules
require_once plugin_dir_path( __FILE__ ) . 'admin/register-post-types.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/restrictions/query-rules.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/restrictions/rest-rules.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/restrictions/set-editable-roles.php';


// Customizations for questions editor
require_once plugin_dir_path( __FILE__ ) . 'admin/questions/force-classic-editor.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/questions/customize-editor.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/questions/question-admin-ui-customizations.php';

// Customizations for applications saving etc.
require_once plugin_dir_path( __FILE__ ) . 'admin/applications/post-creation.php';

// Admin notes scripts

// ---------------------- Front end related scripts ---------------------

// Enq UI shortcodes
require_once plugin_dir_path( __FILE__ ) . 'public/ui-shortcodes.php';
require_once plugin_dir_path( __FILE__ ) . 'public/register-application-scripts.php';
require_once plugin_dir_path( __FILE__ ) . 'public/public-ajax.php';
