<?php
//! ===
//! Do not edit anything in this file unless you know what you're doing
//! ===

// Register parameters
add_action('init','add_get_val');
function add_get_val() { 
    global $wp; 
    $wp->add_query_var('league');
    $wp->add_query_var('success'); // Contact
    $wp->add_query_var('news');  // News Article ID
    $wp->add_query_var('img');   // News Image ID
    $wp->add_query_var('date'); // Date
}