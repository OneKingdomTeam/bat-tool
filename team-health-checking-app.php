<?php

/*
Plugin Name:  Benchamrk Assessment Tool - Plugin
Description:  Creates UI and backend for BAT Tool. Manages users, data, etc.
Plugin URI:   https://vrubel.online/
Author:       Petr Vrubel
Version:      1.2.10
Text Domain:  team-health-checking-app
Domain Path:  /languages
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.txt
*/

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function thet_set_plugin_environment(){

    global $thet_plugin_environment;
    $thet_plugin_environment = [
        'root_dir_path' => plugin_dir_path(__FILE__), // Has trailing slash probably coz of directory
        'root_dir_url' => plugin_dir_url(__FILE__) // also have trailing slash
    ];

}
add_action('init', 'thet_set_plugin_environment', 1);


// ---------------------- Backend Scripts  ---------------------
require_once plugin_dir_path( __FILE__ ) . 'checks.php';

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

// Restriction rules and sets
require_once plugin_dir_path( __FILE__ ) . 'admin/restrictions/query-rules.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/restrictions/rest-rules.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/restrictions/set-editable-roles.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/restrictions/user-access-level-checker.php';



// Customizations for questions editor
require_once plugin_dir_path( __FILE__ ) . 'admin/questions/force-classic-editor.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/questions/customize-editor.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/questions/question-admin-ui-customizations.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/questions/block-add-new-question.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/questions/supportive-functions.php';

// Customizations for applications saving etc.
require_once plugin_dir_path( __FILE__ ) . 'admin/applications/post-creation.php';

// Admin notes code
require_once plugin_dir_path( __FILE__ ) . 'admin/admin-notes/admin-notes.php';


// ---------------------- Front end related scripts ---------------------


// Enq UI shortcodes
require_once plugin_dir_path( __FILE__ ) . 'public/applications/ui-shortcodes.php';
require_once plugin_dir_path( __FILE__ ) . 'public/applications/register-application-scripts.php';
require_once plugin_dir_path( __FILE__ ) . 'public/applications/public-ajax.php';


// ENQ admin notes
require_once plugin_dir_path( __FILE__ ) . 'public/admin-notes/admin-notes.php';
