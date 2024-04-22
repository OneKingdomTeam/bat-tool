<?php

/*
Plugin Name:  Benchamrk Assessment Tool - Plugin
Description:  Creates UI and backend for BAT Tool. Manages users, data, etc.
Plugin URI:   https://vrubel.online/
Author:       Petr Vrubel
Version:      1.3.3
Text Domain:  team-health-checking-app
Domain Path:  /languages
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.txt
*/

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// TODO
/*
 *  Creation of the questions should only run once at activation! create-questions.php
 *
 *
*/


// -----------------------------------------------------------------------------------

    function bat_get_env(){
        // both of them have trailing slash
        static $env = null;
        if( $env === null) {
            $env = [
                'root_dir_path' => plugin_dir_path(__FILE__),
                'root_dir_url' => plugin_dir_url(__FILE__)
            ];
        }
        return $env;
    }

// -----------------------------------------------------------------------------------

    // Registers all required post types
    require_once bat_get_env()['root_dir_path'] . 'includes/register-post-types.php';
    add_action('init', 'bat_register_post_types');

// -----------------------------------------------------------------------------------

    // Handles de/activation of the plugin
    require_once bat_get_env()['root_dir_path'] . 'includes/de-activation.php';

    register_activation_hook( __FILE__, 'bat_tool_activation' );
    register_deactivation_hook( __FILE__, 'bat_tool_deactivation' );


// -----------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------

    // Restriction
    require_once bat_get_env()['root_dir_path'] . 'includes/restrictions/load-restrictions.php';

    // Application requirements
    require_once bat_get_env()['root_dir_path'] . 'admin/applications/query-rules.php';
    require_once bat_get_env()['root_dir_path'] . 'admin/applications/application-save-hook-events.php';
    require_once bat_get_env()['root_dir_path'] . 'admin/applications/application-ajax.php';

    require_once bat_get_env()['root_dir_path'] . 'public/applications/register-applications-scripts-and-styles.php';
    require_once bat_get_env()['root_dir_path'] . 'public/applications/shortcodes.php';

    // Questions requirements
    require_once bat_get_env()['root_dir_path'] . 'includes/questions/hide-add-new.php';
    require_once bat_get_env()['root_dir_path'] . 'includes/questions/force-classic-editor.php';
    require_once bat_get_env()['root_dir_path'] . 'includes/questions/customize-query.php';
    require_once bat_get_env()['root_dir_path'] . 'includes/questions/customize-editor.php';
    require_once bat_get_env()['root_dir_path'] . 'includes/questions/customize-columns.php';

    // Reports notes files
    require_once bat_get_env()['root_dir_path'] . 'includes/reports/customize-password-enter-page.php';

    // Admin notes files
    require_once bat_get_env()['root_dir_path'] . 'includes/admin-notes/database-functions.php';
    require_once bat_get_env()['root_dir_path'] . 'includes/admin-notes/notes-ajax.php';

    require_once bat_get_env()['root_dir_path'] . 'admin/admin-notes/register-scripts-and-styles.php';

