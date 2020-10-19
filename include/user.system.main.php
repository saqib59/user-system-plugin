<?php
if (!class_exists('USER_SYSTEM_Main')) {
    return;
}
class USER_SYSTEM_Main
{
    public function __construct()
    {
        /**
        
        * include all required files
        
        */
        require_once __DIR__ . '/user-system-shorcodes.php';
        require_once __DIR__ . '/user-system-custom.php';
        require_once __DIR__ . '/user-system-payment.php';
        require_once __DIR__ . '/user-system-ajax.php';
        /**
        
        * main hooks
        
        */
        add_action('init', array(
            $this,
            'init'
        ));
    }
    public function init()
    {
        $this->init_user_system_custom_pages();
        //$this->init_post_types();
    }
    public function init_user_system_custom_pages()
    {
        $user_id                          = get_current_user_id();
        // Create post object
        $register_page_availability       = get_page_title_for_slug('user-system-register');
        $login_page_availability          = get_page_title_for_slug('user-system-login');
        $dashboard_page_availability      = get_page_title_for_slug('user-system-dashboard');
        $user_page_messaging_availability = get_page_title_for_slug('user-system-messaging');
        $user_admin_files_availability    = get_page_title_for_slug('user-system-admin-files');
        $user_profile_page_availability   = get_page_title_for_slug('user-system-edit-profile');
        if ($register_page_availability == false) {
            $register_post = array(
                'post_title' => wp_strip_all_tags('User System Register'),
                'post_name' => 'user-system-register',
                'post_content' => '[user_system_registration_form]',
                'post_status' => 'publish',
                'post_author' => $user_id,
                'post_type' => 'page'
            );
            // Insert the post into the database
            wp_insert_post($register_post);
        }
        if ($login_page_availability == false) {
            $login_post = array(
                'post_title' => wp_strip_all_tags('User System Login'),
                'post_name' => 'user-system-login',
                'post_content' => '[user_system_login_form]',
                'post_status' => 'publish',
                'post_author' => $user_id,
                'post_type' => 'page'
            );
            // Insert the post into the database
            wp_insert_post($login_post);
        }
        if ($dashboard_page_availability == false) {
            $dashboard_post = array(
                'post_title' => wp_strip_all_tags('User System Dashboard'),
                'post_name' => 'user-system-dashboard',
                'post_content' => '[user_system_dashboard_form]',
                'post_status' => 'publish',
                'post_author' => $user_id,
                'post_type' => 'page'
            );
            // Insert the post into the database
            wp_insert_post($dashboard_post);
        }
        if ($user_page_messaging_availability == false) {
            $messaging_post = array(
                'post_title' => wp_strip_all_tags('User System Messaging'),
                'post_name' => 'user-system-messaging',
                'post_content' => '[user_system_messaging_form]',
                'post_status' => 'publish',
                'post_author' => $user_id,
                'post_type' => 'page'
            );
            // Insert the post into the database
            wp_insert_post($messaging_post);
        }
        if ($user_admin_files_availability == false) {
            $messaging_post = array(
                'post_title' => wp_strip_all_tags('User System Admin Files'),
                'post_name' => 'user-system-admin-files',
                'post_content' => '[user_system_adminfiles_form]',
                'post_status' => 'publish',
                'post_author' => $user_id,
                'post_type' => 'page'
            );
            // Insert the post into the database
            wp_insert_post($messaging_post);
        }
        if ($user_profile_page_availability == false) {
            $edit_profile = array(
                'post_title' => wp_strip_all_tags('User System Edit Profile'),
                'post_name' => 'user-system-edit-profile',
                'post_content' => '[user_system_editprofile_form]',
                'post_status' => 'publish',
                'post_author' => $user_id,
                'post_type' => 'page'
            );
            // Insert the post into the database
            wp_insert_post($edit_profile);
        }
    }
    public function init_post_types()
    {
        // Registering custom post type 'shears members posts'
        $shears_members_args = array(
            'labels' => array(
                'name' => __("Shear'ree Posts", 'shears-membership'),
                'menu_name' => __("Shear'ree Post", 'shears-membership'),
                'singular_name' => __("Shear'ree Post", 'shears-membership')
            ),
            'public' => true,
            'show_ui' => true,
            'publicly_queryable' => true,
            'has_archive' => true,
            'show_in_menu' => true,
            //'supports' => array('title', 'editor', 'thumbnail')
            'supports' => array(
                'title',
                'editor',
                'excerpt',
                'thumbnail'
            )
        );
        register_post_type('shear_post', $shears_members_args);
    }
    public function init_meta_fields()
    {
        $cmb_shear_post = new_cmb2_box(array(
            'id' => 'shear_post_metabox',
            'title' => esc_html__('Shear Post - Custom  Fields'),
            'object_types' => array(
                'shear_post'
            )
        ));
        $cmb_shear_post->add_field(array(
            'name' => 'Select Post for members',
            'desc' => 'Select a member',
            'id' => 'shear_members_post',
            'type' => 'select',
            'show_option_none' => false,
            'default' => 'custom',
            'options' => array(
                '0' => __('Free Members', 'cmb2'),
                '1' => __('Paid Members', 'cmb2'),
                '2' => __('Both (Free & Paid) Members', 'cmb2')
            )
        ));
        $cmb_shear_post->add_field(array(
            'id' => 'shears_members_id',
            'type' => 'hidden',
            'default' => '0'
        ));
        $cmb_shear_post->add_field(array(
            'id' => 'shears_options_status',
            'type' => 'hidden',
            'default' => 'free'
        ));
    }
}
new USER_SYSTEM_Main;


