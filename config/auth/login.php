<?php

// global $current_user;
// get_currentuserinfo();

// print_r($current_user);

// // Show Login Welcome
// add_action( 'wp_login', 'welcome_user' );
// function welcome_user( $current_user ) {

    

//     wp_enqueue_script('uikit', 'https://cdn.jsdelivr.net/npm/uikit@3.9.1/dist/js/uikit.min.js', ['jquery'], null, true);
//     if ( $current_user->ID != '' ) {
//         wp_enqueue_script( 'um_account_error', get_template_directory_uri().'/resources/scripts/um/login_msg.js', ['jquery'], null, true );
//     }

// }

// Check for errors
add_action( 'um_submit_form_errors_hook_logincheck', 'loginCheck', 10, 1 );
function loginCheck($args)
{
    global $ultimatemember;
    $is_email = false;
    $form_id = $args['form_id'];
    $mode = $args['mode'];

    wp_enqueue_script('uikit', 'https://cdn.jsdelivr.net/npm/uikit@3.9.1/dist/js/uikit.min.js', ['jquery'], null, true);
    if (isset($args['username']) && $args['username'] == '') {
        wp_enqueue_script( 'um_account_error', _scripts.'um/account_error.js', ['jquery'], null, true );
    }
    if (isset($args['user_login']) && $args['user_login'] == '') {
        wp_enqueue_script( 'um_account_error', _scripts.'um/account_error.js', ['jquery'], null, true );
    }
    if (isset($args['user_email']) && $args['user_email'] == '') {
        wp_enqueue_script( 'um_account_error', _scripts.'um/account_error.js', ['jquery'], null, true );
    }
    if (isset($args['username'])) {
        $field = 'username';
        if (is_email($args['username'])) {
            $is_email = true;
            $data = get_user_by('email', $args['username']);
            $user_name = isset($data->user_login) ? $data->user_login : null;
        } else {
            $user_name = $args['username'];
        }
    } else {
        if (isset($args['user_email'])) {
            $field = 'user_email';
            $is_email = true;
            $data = get_user_by('email', $args['user_email']);
            $user_name = isset($data->user_login) ? $data->user_login : null;
        } else {
            $field = 'user_login';
            $user_name = $args['user_login'];
        }
    }
    if (!username_exists($user_name)) {
        if ($is_email) {
            wp_enqueue_script( 'um_account_error', _scripts.'um/account_error.js', ['jquery'], null, true );
        } else {
            wp_enqueue_script( 'um_account_error', _scripts.'um/account_error.js', ['jquery'], null, true );
        }
    } else {
        if ($args['user_password'] == '') {
            wp_enqueue_script( 'um_account_error', _scripts.'um/account_error.js', ['jquery'], null, true );
        }
    }
    $user = get_user_by('login', $user_name);
    if ($user && wp_check_password($args['user_password'], $user->data->user_pass, $user->ID)) {
        $ultimatemember->login->auth_id = username_exists($user_name);
    } else {
        wp_enqueue_script( 'um_account_error', _scripts.'um/account_error.js', ['jquery'], null, true );
    }
}

// Remove Lost Password Link
function vpsb_remove_lostpassword_text ( $text ) {
    if ($text == 'Lost your password?'){$text = '';}
        return $text;
    }
add_filter( 'gettext', 'vpsb_remove_lostpassword_text' );