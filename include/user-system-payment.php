<?php
require_once('stripe/vendor/autoload.php');
add_action('wp_ajax_register_form', 'register_form');
add_action('wp_ajax_nopriv_register_form', 'register_form');
function register_form()
{
    if (empty($_POST['user-name']) || empty($_POST['user-email']) || empty($_POST['user-password'])) {
        //throw new Exception("Fill out all required fields.");
        $err['error']  = 'Fill out all required fields.';
        $err['status'] = false;
        echo json_encode($err);
        wp_die();
    }
    if (email_exists(trim($_POST['user-email']))) {
        $err['error']  = 'The E-mail , you enetered is already registered, Try another one.';
        $err['status'] = false;
        echo json_encode($err);
        wp_die();
    }
    if (username_exists(trim($_POST['user-name']))) {
        $err['error']  = 'Username , you enetered is already registered, Try another one.';
        $err['status'] = false;
        echo json_encode($err);
        wp_die();
    }
    $user_role = trim($_POST['user-role']);
    if ($user_role == 'staff') {
        $user_role_selected = 'user_system';
    } elseif ($user_role == 'patient') {
        $user_role_selected = 'user_system';
    } elseif ($user_role == 'student') {
        $user_role_selected = 'user_system';
    }
    $userdata = array(
        'user_login' => $_POST['user-name'],
        'user_pass' => $_POST['user-password'], // When creating a new user, `user_pass` is expected.
        'user_email' => $_POST['user-email'],
        'role' => 'user_system',
    );
    $user_id  = wp_insert_user($userdata);
    if (!is_wp_error($user_id)) {
        add_user_meta($user_id, 'user_system_user_status', 1, true);
        $err['status']       = true;
        $err['redirect_url'] = home_url('/user-system-login/');
    } else {
        $err['error']  = 'Oops ! User is not created.It seems that , user already exists';
        $err['status'] = false;
    }
    echo json_encode($err);
    wp_die();
}
add_action('wp_ajax_login_form', 'login_form');
add_action('wp_ajax_nopriv_login_form', 'login_form');
function login_form()
{
    $user_system_email    = $_POST['user_system_email'];
    $user_system_password = $_POST['user_system_password'];
    if (empty($user_system_email)) {
        $emailErr      = "Email/User Login is required";
        $err['error']  = $emailErr;
        $err['status'] = false;
    } else {
        $user_system_email = user_system_test_input($user_system_email);
        // check if e-mail address is well-formed
        if (!filter_var($user_system_email, FILTER_VALIDATE_EMAIL)) {
            // it's not a email
            $userlogin_emailStatus = true;
        } else {
            // it's not a login
            $userlogin_emailStatus = false;
        }
    }
    //password
    if (empty($user_system_password)) {
        $passwordErr   = "Password is required";
        $err['error']  = $passwordErr;
        $err['status'] = false;
    } else {
        $user_system_password = user_system_test_input($user_system_password);
    }
    //REMEMBER ME
    if (isset($_POST['user_system_remember'])) {
        $remember_me = true;
    } else {
        $remember_me = false;
    }
    if ($userlogin_emailStatus == true) {
        $get_user = get_user_by('login', $user_system_email);
    } else {
        $get_user = get_user_by('email', $user_system_email);
    }
    //$get_user = get_user_by( 'email', $shears_email );
    if ($get_user != false) {
        $user_role = $get_user->roles[0];
        if ($user_role == 'administrator') {
            $user_status = 1;
        } else {
            $user_status = get_user_meta($get_user->ID, 'user_system_user_status', true);
        }
        if ($user_status == 2) {
            $passwordErr = "Your request is still in progress.. Have Patience..!!!";
        } else if ($user_status == 1) {
            if (wp_check_password($user_system_password, $get_user->data->user_pass, $get_user->ID)) {
                $creds = array(
                    'user_login' => $get_user->data->user_login,
                    'user_password' => $user_system_password,
                    'remember' => $remember_me
                );
                $user  = wp_signon($creds, false);
                if (is_wp_error($user)) {
                    $passwordErr   = "Can't login";
                    $err['error']  = $passwordErr;
                    $err['status'] = false;
                    //return $user->get_error_message();
                } else {
                    $redirect_dashboard  = home_url('/user-system-dashboard');
                    $err['status']       = true;
                    $err['redirect_url'] = $redirect_dashboard;
                }
            } else {
                $passwordErr   = "Password is inncorrect";
                $err['error']  = $passwordErr;
                $err['status'] = false;
            }
        } elseif ($user_status == 2) {
            $passwordErr   = "You are disabled by an admin. Contact to an admin.";
            $err['error']  = $passwordErr;
            $err['status'] = false;
        } elseif ($user_status == 3) {
            $passwordErr   = "You'r Account is off. Contact to an Admin.";
            $err['error']  = $passwordErr;
            $err['status'] = false;
        }
    } else {
        $emailErr      = "User with this email doesn't exists";
        $err['error']  = $emailErr;
        $err['status'] = false;
    }
    echo json_encode($err);
    wp_die();
}
add_action('wp_ajax_create_venue', 'create_venue');
add_action('wp_ajax_nopriv_create_venue', 'create_venue');
function create_venue()
{
    $post_id = wp_insert_post(array(
        'post_title' => $_POST['title'],
        'post_type' => 'venue',
        'post_content' => $_POST['description']
    ));
    update_post_meta($post_id, 'from_date', $_POST['from_date'], true);
    update_post_meta($post_id, 'to_date', $_POST['to_date'], true);
    update_post_meta($post_id, 'quantity', $_POST['quantity'], true);
    update_post_meta($post_id, 'fb_link', $_POST['fb-link'], true);
    update_post_meta($post_id, 'google_link', $_POST['google-link'], true);
    update_post_meta($post_id, 'twitter_link', $_POST['twitter-link'], true);
    echo json_encode(array(
        'status' => true,
        'message' => 'Venue has been created',
        'redirect_url' => home_url('/')
    ));
    wp_die();
}
/** WOOCOMERCE SETUP CHECK **/
add_filter('woocommerce_is_purchasable', 'medicine_restrictions', 20, 2);
function medicine_restrictions($purchasable, $product)
{
    global $post;
    $current_user    = wp_get_current_user();
    $product_id      = $post->ID;
    $product_status  = get_post_meta($product_id, 'product_status', true);
    /*if($product_status == 'yes'){
    
    var_export($product_status);
    
    }*/
    $current_user_id = $current_user->ID;
    if ($current_user_id == 0) {
        if ($product_status == 'yes') {
            $purchasable = false;
        } else {
            $purchasable = true;
        }
    } else if ($current_user_id != 0) {
        $current_user_status = get_user_meta($current_user_id, 'paid-status', true); // paid status
        if (isset($current_user_status) && !empty($current_user_status) && $current_user_status == 'yes') {
            if ($product_status == 'yes') {
                $purchasable = true;
            } else {
                $purchasable = true;
            }
        } else {
            if ($product_status == 'yes') {
                $purchasable = false;
            } else {
                $purchasable = true;
            }
        }
    }
    return $purchasable;
}
add_action('woocommerce_single_product_summary', 'unavailable_product_display_message', 20);
function unavailable_product_display_message()
{
    global $product;
    if (!$product->is_purchasable()) {
        echo '<p style="color:#e00000;">' . __("You need to subscribe to the membership in order to purchase the product.") . '</p>';
    }
}




?>