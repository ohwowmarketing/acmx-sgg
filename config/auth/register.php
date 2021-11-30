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

/**
 * Ultimate Member 2.0 - Customization
 * Description: Allow everyone to upload profile registration pages.
 */
add_filter("um_user_pre_updating_files_array","um_custom_user_pre_updating_files_array", 10, 1);
function um_custom_user_pre_updating_files_array( $arr_files ){
   
   if( is_array( $arr_files ) ){
      foreach( $arr_files as $key => $details ){
           if( $key == "user_photo" ){
                 unset( $arr_files[ $key ] );
                  $arr_files[ "profile_photo" ] = $details;
           } 
      } 
   }   
 
   return $arr_files;
}

add_filter("um_allow_frontend_image_uploads","um_custom_allow_frontend_image_uploads",10, 3);
function um_custom_allow_frontend_image_uploads( $allowed, $user_id, $key ){
  
  if( $key == "profile_photo" ){
       return true;  
  }
 
  return $allowed; // false
}


// um-short-functions.php
// In line 1760 function um_get_avatar_uri( $image, $attrs )

// function um_get_avatar_uri( $image, $attrs ) {
//     $uri = false;
//     $uri_common = false;
//     $find = false;
//     $ext = '.' . pathinfo( $image, PATHINFO_EXTENSION );
//     $custom_profile_photo = get_user_meta(um_user( 'ID' ), 'profile_photo', 'true');

//     if ( is_multisite() ) {
//         //multisite fix for old customers
//         $multisite_fix_dir = UM()->uploader()->get_upload_base_dir();
//         $multisite_fix_url = UM()->uploader()->get_upload_base_url();
//         $multisite_fix_dir = str_replace( DIRECTORY_SEPARATOR . 'sites' . DIRECTORY_SEPARATOR . get_current_blog_id() . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $multisite_fix_dir );
//         $multisite_fix_url = str_replace( '/sites/' . get_current_blog_id() . '/', '/', $multisite_fix_url );


//         if ( $attrs == 'original' && file_exists( $multisite_fix_dir . um_user( 'ID' ) . DIRECTORY_SEPARATOR . "profile_photo{$ext}" ) ) {
//             $uri_common = $multisite_fix_url . um_user( 'ID' ) . "/profile_photo{$ext}";        
//         } elseif ( file_exists( $multisite_fix_dir . um_user( 'ID' ) . DIRECTORY_SEPARATOR . "profile_photo-{$attrs}x{$attrs}{$ext}" ) ) {
//             $uri_common = $multisite_fix_url . um_user( 'ID' ) . "/profile_photo-{$attrs}x{$attrs}{$ext}";
//         } elseif ( file_exists( $multisite_fix_dir . um_user( 'ID' ) . DIRECTORY_SEPARATOR . "profile_photo-{$attrs}{$ext}" ) ) {
//             $uri_common = $multisite_fix_url . um_user( 'ID' ) . "/profile_photo-{$attrs}{$ext}";
//         } else {
//             $sizes = UM()->options()->get( 'photo_thumb_sizes' );
//             if ( is_array( $sizes ) ) {
//                 $find = um_closest_num( $sizes, $attrs );
//             }

//             if ( file_exists( $multisite_fix_dir . um_user( 'ID' ) . DIRECTORY_SEPARATOR . "profile_photo-{$find}x{$find}{$ext}" ) ) {
//                 $uri_common = $multisite_fix_url . um_user( 'ID' ) . "/profile_photo-{$find}x{$find}{$ext}";
//             } elseif ( file_exists( $multisite_fix_dir . um_user( 'ID' ) . DIRECTORY_SEPARATOR . "profile_photo-{$find}{$ext}" ) ) {
//                 $uri_common = $multisite_fix_url . um_user( 'ID' ) . "/profile_photo-{$find}{$ext}";
//             } elseif ( file_exists( $multisite_fix_dir . um_user( 'ID' ) . DIRECTORY_SEPARATOR . "profile_photo{$ext}" ) ) {
//                 $uri_common = $multisite_fix_url . um_user( 'ID' ) . "/profile_photo{$ext}";
//             }
//         }
//     }

//     if ( $attrs == 'original' && file_exists( UM()->uploader()->get_upload_base_dir() . um_user( 'ID' ) . DIRECTORY_SEPARATOR . "profile_photo{$ext}" ) ) {
//         $uri = UM()->uploader()->get_upload_base_dir()  . um_user( 'ID' ) . "/profile_photo{$ext}";
//     } elseif ( file_exists( UM()->uploader()->get_upload_base_dir() . um_user( 'ID' ) . DIRECTORY_SEPARATOR . $custom_profile_photo ) ) {
//         $uri = UM()->uploader()->get_upload_base_url() . um_user( 'ID' ) . DIRECTORY_SEPARATOR . $custom_profile_photo;     
//     } elseif ( file_exists( UM()->uploader()->get_upload_base_dir() . um_user( 'ID' ) . DIRECTORY_SEPARATOR . "profile_photo-{$attrs}x{$attrs}{$ext}" ) ) {
//         $uri = UM()->uploader()->get_upload_base_url() . um_user( 'ID' ) . "/profile_photo-{$attrs}x{$attrs}{$ext}";
//     } elseif ( file_exists( UM()->uploader()->get_upload_base_dir() . um_user( 'ID' ) . DIRECTORY_SEPARATOR . "profile_photo-{$attrs}{$ext}" ) ) {
//         $uri = UM()->uploader()->get_upload_base_url() . um_user( 'ID' ) . "/profile_photo-{$attrs}{$ext}";
//     } else {
//         $sizes = UM()->options()->get( 'photo_thumb_sizes' );
//         if ( is_array( $sizes ) ) {
//             $find = um_closest_num( $sizes, $attrs );
//         }

//         if ( file_exists( UM()->uploader()->get_upload_base_dir() . um_user( 'ID' ) . DIRECTORY_SEPARATOR . "profile_photo-{$find}x{$find}{$ext}" ) ) {
//             $uri = UM()->uploader()->get_upload_base_url() . um_user( 'ID' ) . "/profile_photo-{$find}x{$find}{$ext}";
//         } elseif ( file_exists( UM()->uploader()->get_upload_base_dir() . um_user( 'ID' ) . DIRECTORY_SEPARATOR . "profile_photo-{$find}{$ext}" ) ) {
//             $uri = UM()->uploader()->get_upload_base_url() . um_user( 'ID' ) . "/profile_photo-{$find}{$ext}";
//         } elseif ( file_exists( UM()->uploader()->get_upload_base_dir() . um_user( 'ID' ) . DIRECTORY_SEPARATOR . "profile_photo{$ext}" ) ) {
//             $uri = UM()->uploader()->get_upload_base_url() . um_user( 'ID' ) . "/profile_photo{$ext}";
//         }
//     }

//     if ( ! empty( $uri_common ) && empty( $uri ) ) {
//         $uri = $uri_common;
//     }

//     $cache_time = apply_filters( 'um_filter_avatar_cache_time', current_time( 'timestamp' ), um_user( 'ID' ) );
//     if ( ! empty( $cache_time ) ) {
//         $uri .= "?{$cache_time}";
//     }

//     return $uri;
// }