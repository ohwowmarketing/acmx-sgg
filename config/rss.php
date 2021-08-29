<?php
//! ===
//! Do not edit anything in this file unless you know what you're doing
//! ===

function rss_post_thumbnail($content) {
    global $post;
    if(has_post_thumbnail($post->ID)) {
        $content = '<figure>' . get_the_post_thumbnail($post->ID) .
        '</figure>' . get_the_content();
    }
    return $content;
}
add_filter('the_excerpt_rss', 'rss_post_thumbnail');
add_filter('the_content_feed', 'rss_post_thumbnail');


add_action('init', 'customRSS');
function customRSS(){
    add_feed('guides', 'customRSSFunc');
}

function customRSSFunc(){
    get_template_part('rss', 'guides');
}