<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// since all of those are used almost all the time...
function bat_register_post_types(){

    $env = bat_get_env();
    require_once $env['root_dir_path'] . 'includes/applications/register-applications.php';
    require_once $env['root_dir_path'] . 'includes/questions/register-questions.php';
    require_once $env['root_dir_path'] . 'includes/reports//register-reports.php';

    bat_register_applications();
    bat_register_questions();
    bat_register_reports();

}
