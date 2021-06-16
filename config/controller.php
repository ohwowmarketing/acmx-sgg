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

function makeUrltoLink($string) {
    // The Regular Expression filter
    $reg_pattern = "/(((http|https|ftp|ftps)\:\/\/)|(www\.))[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/";
    // make the urls to hyperlinks
    return preg_replace($reg_pattern, '<a href="$0" target="_blank" rel="noopener noreferrer">$0</a>', $string);
}

// Example usage
// $str = "Visit www.cluemediator.com and subscribe us on https://www.cluemediator.com/subscribe for regular updates.";
// echo $convertedStr = makeUrltoLink($str);