<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'disable-other-users-edit-admin-user.php';
require_once plugin_dir_path(__FILE__) . 'hide-wp-admin.php';
require_once plugin_dir_path(__FILE__) . 'login-redirect.php';
require_once plugin_dir_path(__FILE__) . 'set-editable-roles.php';
