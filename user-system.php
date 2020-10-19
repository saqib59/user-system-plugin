<?php
/**
* Plugin Name: User System
* Plugin URI: https://www.google.com.pk/
* Description: This plugin for user system
* Version: 1.0.0
* Author: Optimist
* Author URI: https://www.google.com.pk
*
* Domain Path: /i18n/languages/
*
* @author Optimist
*/


// echo 'Current PHP version: ' . phpversion();
define('USER_PLUGIN_DIR', plugin_dir_path(__FILE__));
register_deactivation_hook(__FILE__, 'deactivate_related_data');
function deactivate_related_data(){
    $register_page_availability       = get_page_title_for_slug('user-system-register');
    $login_page_availability          = get_page_title_for_slug('user-system-login');
    $dashboard_page_availability      = get_page_title_for_slug('user-system-dashboard');
    $user_page_messaging_availability = get_page_title_for_slug('user-system-messaging');
    $user_admin_files_availability    = get_page_title_for_slug('user-system-admin-files');
    $user_profile_page_availability   = get_page_title_for_slug('user-system-edit-profile');
    //$single_page_availability = get_page_title_for_slug('shears-single');
    if ($register_page_availability == true) {
        $page_delete = get_page_by_path('user-system-register');
        wp_delete_post($page_delete->ID, true);
    }
    if ($login_page_availability == true) {
        $page_delete = get_page_by_path('user-system-login');
        wp_delete_post($page_delete->ID, true);
    }
    if ($dashboard_page_availability == true) {
        $page_delete = get_page_by_path('user-system-dashboard');
        wp_delete_post($page_delete->ID, true);
    }
    if ($user_page_messaging_availability == true) {
        $page_delete = get_page_by_path('user-system-messaging');
        wp_delete_post($page_delete->ID, true);
    }
    if ($user_admin_files_availability == true) {
        $page_delete = get_page_by_path('user-system-admin-files');
        wp_delete_post($page_delete->ID, true);
    }
    if ($user_profile_page_availability == true) {
        $page_delete = get_page_by_path('user-system-edit-profile');
        wp_delete_post($page_delete->ID, true);
    }
    // unregister the post type, so the rules are no longer in memory
    unregister_post_type('user_system');
    // clear the permalinks to remove our post type's rules from the database
    flush_rewrite_rules();
}
require_once __DIR__ . '/include/user.system.main.php';































