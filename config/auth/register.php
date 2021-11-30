<?php

add_action( 'um_submit_form_errors_hook__registration', 'my_submit_form_errors_registration', 10, 1 );
function my_submit_form_errors_registration( $args ) {

    global $ultimatemember;
    $form_id = $args['form_id'];
    $mode = $args['mode'];
    
    wp_enqueue_script('uikit', 'https://cdn.jsdelivr.net/npm/uikit@3.9.1/dist/js/uikit.min.js', ['jquery'], null, true);
    if ( $args['user_login'] == '' ) {
        wp_enqueue_script( 'um_registration_error', _scripts.'um/register_error.js', ['jquery'], null, true );
    }

    if ( $mode == 'register' && username_exists( sanitize_user( $args['user_login'] ) ) ) {
        wp_enqueue_script( 'um_registration_error', _scripts.'um/register_error.js', ['jquery'], null, true );
    } 

    if ( is_email( $args['user_login'] ) ) {
        wp_enqueue_script( 'um_registration_error', _scripts.'um/register_error.js', ['jquery'], null, true );
    } 

    if ( ! UM()->validation()->safe_username( $args['user_login'] ) ) {
        wp_enqueue_script( 'um_registration_error', _scripts.'um/register_error.js', ['jquery'], null, true );
    }

    if ( $mode == 'register' && email_exists( $args['user_email'] ) ) {
        wp_enqueue_script( 'um_registration_error', _scripts.'um/register_error.js', ['jquery'], null, true );
    }

}